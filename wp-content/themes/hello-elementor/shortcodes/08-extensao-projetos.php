<?php
/**
 * Shortcode: [extensao_projetos_excel]
 * Description: Exibe os projetos de extensao a partir da planilha oficial enviada ao WordPress.
 */

if ( ! function_exists( 'fisica_get_extensao_excel_path' ) ) {
	/**
	 * Return the official Excel path for the Extensao page.
	 *
	 * @return string
	 */
	function fisica_get_extensao_excel_path() {
		$upload_dir = wp_upload_dir();

		return trailingslashit( $upload_dir['basedir'] ) . '2026/05/Projetos_EXT_FIS_Site_do_IF.xlsx';
	}
}

if ( ! function_exists( 'fisica_get_extensao_xml_text' ) ) {
	/**
	 * Extract plain text from a SimpleXML string item.
	 *
	 * @param SimpleXMLElement $node XML node.
	 *
	 * @return string
	 */
	function fisica_get_extensao_xml_text( $node ) {
		if ( isset( $node->t ) ) {
			return (string) $node->t;
		}

		$text = '';

		if ( isset( $node->r ) ) {
			foreach ( $node->r as $run ) {
				$text .= isset( $run->t ) ? (string) $run->t : '';
			}
		}

		return $text;
	}
}

if ( ! function_exists( 'fisica_read_extensao_projects' ) ) {
	/**
	 * Read the Extensao projects directly from the official Excel file.
	 *
	 * @return array<int, array<string, string>>
	 */
	function fisica_read_extensao_projects() {
		static $cache = null;

		if ( null !== $cache ) {
			return $cache;
		}

		$cache = [];
		$file  = fisica_get_extensao_excel_path();

		if ( ! file_exists( $file ) || ! is_readable( $file ) ) {
			return $cache;
		}

		if ( ! class_exists( 'PclZip' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-pclzip.php';
		}

		if ( ! class_exists( 'PclZip' ) ) {
			return $cache;
		}

		$archive = new PclZip( $file );
		$entries = $archive->extract(
			PCLZIP_OPT_BY_NAME,
			[
				'xl/sharedStrings.xml',
				'xl/worksheets/sheet1.xml',
			],
			PCLZIP_OPT_EXTRACT_AS_STRING
		);

		if ( ! is_array( $entries ) || empty( $entries ) ) {
			return $cache;
		}

		$shared_xml = '';
		$sheet_xml  = '';

		foreach ( $entries as $entry ) {
			if ( empty( $entry['filename'] ) || ! isset( $entry['content'] ) ) {
				continue;
			}

			if ( 'xl/sharedStrings.xml' === $entry['filename'] ) {
				$shared_xml = (string) $entry['content'];
			}

			if ( 'xl/worksheets/sheet1.xml' === $entry['filename'] ) {
				$sheet_xml = (string) $entry['content'];
			}
		}

		if ( '' === $sheet_xml ) {
			return $cache;
		}

		$shared_strings = [];

		if ( '' !== $shared_xml ) {
			$shared = simplexml_load_string( $shared_xml );

			if ( $shared && isset( $shared->si ) ) {
				foreach ( $shared->si as $string_item ) {
					$shared_strings[] = fisica_get_extensao_xml_text( $string_item );
				}
			}
		}

		$sheet = simplexml_load_string( $sheet_xml );

		if ( ! $sheet || ! isset( $sheet->sheetData->row ) ) {
			return $cache;
		}

		foreach ( $sheet->sheetData->row as $row ) {
			$row_number = (int) $row['r'];

			if ( $row_number < 2 ) {
				continue;
			}

			$cells = [
				'A' => '',
				'B' => '',
				'C' => '',
				'D' => '',
			];

			foreach ( $row->c as $cell ) {
				$reference = (string) $cell['r'];
				$column    = preg_replace( '/\d+/', '', $reference );
				$type      = (string) $cell['t'];
				$value     = '';

				if ( 's' === $type && isset( $cell->v ) ) {
					$index = (int) $cell->v;
					$value = $shared_strings[ $index ] ?? '';
				} elseif ( isset( $cell->is ) ) {
					$value = fisica_get_extensao_xml_text( $cell->is );
				} elseif ( isset( $cell->v ) ) {
					$value = (string) $cell->v;
				}

				if ( isset( $cells[ $column ] ) ) {
					$cells[ $column ] = trim( html_entity_decode( $value, ENT_QUOTES, 'UTF-8' ) );
				}
			}

			if ( '' === $cells['A'] ) {
				continue;
			}

			$cache[] = [
				'title'       => $cells['A'],
				'email'       => $cells['B'],
				'coordinator' => $cells['C'],
				'summary'     => $cells['D'],
			];
		}

		$cache = array_slice( $cache, 0, 17 );

		return $cache;
	}
}

if ( ! function_exists( 'fisica_render_extensao_project_card' ) ) {
	/**
	 * Render a single project card.
	 *
	 * @param array<string, string> $project Project data.
	 * @param int                   $index   1-based index.
	 * @param string                $uid     Section uid.
	 *
	 * @return string
	 */
	function fisica_render_extensao_project_card( $project, $index, $uid ) {
		$modal_id = $uid . '-modal-' . $index;
		$badge    = str_pad( (string) $index, 2, '0', STR_PAD_LEFT );

		return sprintf(
			'<button type="button" class="quadrado-servico quadrado-servico--button" data-extensao-modal-trigger="%1$s" aria-controls="%1$s" aria-label="%2$s"><span class="quadrado-conteudo"><span class="quadrado-servico__indice">%3$s</span><h3>%2$s</h3></span></button>',
			esc_attr( $modal_id ),
			esc_html( $project['title'] ),
			esc_html( $badge )
		);
	}
}

if ( ! function_exists( 'fisica_render_extensao_project_modal' ) ) {
	/**
	 * Render a single project modal.
	 *
	 * @param array<string, string> $project Project data.
	 * @param int                   $index   1-based index.
	 * @param string                $uid     Section uid.
	 *
	 * @return string
	 */
	function fisica_render_extensao_project_modal( $project, $index, $uid ) {
		$modal_id   = $uid . '-modal-' . $index;
		$title_id   = $modal_id . '-title';
		$summary    = wpautop( esc_html( $project['summary'] ) );
		$email_html = '';

		if ( '' !== $project['email'] ) {
			$email_html = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( 'mailto:' . sanitize_email( $project['email'] ) ),
				esc_html( $project['email'] )
			);
		}

		return sprintf(
			'<dialog class="fisica-extensao-modal" id="%1$s" aria-labelledby="%2$s"><div class="fisica-extensao-modal__panel"><button type="button" class="fisica-extensao-modal__close" data-extensao-modal-close aria-label="Fechar">×</button><h3 class="fisica-extensao-modal__title" id="%2$s">%3$s</h3><div class="fisica-extensao-modal__meta"><div class="fisica-extensao-modal__field"><span class="fisica-extensao-modal__label">E-mail</span><div class="fisica-extensao-modal__value">%4$s</div></div><div class="fisica-extensao-modal__field"><span class="fisica-extensao-modal__label">Coordenador</span><div class="fisica-extensao-modal__value">%5$s</div></div></div><div class="fisica-extensao-modal__field fisica-extensao-modal__field--summary"><span class="fisica-extensao-modal__label">Resumo</span><div class="fisica-extensao-modal__summary">%6$s</div></div></div></dialog>',
			esc_attr( $modal_id ),
			esc_attr( $title_id ),
			esc_html( $project['title'] ),
			$email_html ? $email_html : '&nbsp;',
			esc_html( $project['coordinator'] ),
			$summary
		);
	}
}

if ( ! function_exists( 'shortcode_extensao_projetos_excel' ) ) {
	/**
	 * Render the Extensao projects page content.
	 *
	 * @return string
	 */
	function shortcode_extensao_projetos_excel() {
		$projects = fisica_read_extensao_projects();

		if ( empty( $projects ) ) {
			return '';
		}

		$uid = 'fisica-extensao-' . wp_generate_uuid4();

		ob_start();
		?>
		<section class="fisica-programas fisica-extensao-programas" id="<?php echo esc_attr( $uid ); ?>" aria-labelledby="<?php echo esc_attr( $uid ); ?>-title">
			<div class="fisica-programas__header">
				<span class="fisica-programas__eyebrow">Extensão</span>
			</div>

			<div class="grid-quadrados-servicos">
				<?php foreach ( $projects as $index => $project ) : ?>
					<?php echo fisica_render_extensao_project_card( $project, $index + 1, $uid ); ?>
				<?php endforeach; ?>
			</div>

			<div class="fisica-extensao-modals" aria-hidden="true">
				<?php foreach ( $projects as $index => $project ) : ?>
					<?php echo fisica_render_extensao_project_modal( $project, $index + 1, $uid ); ?>
				<?php endforeach; ?>
			</div>
		</section>
		<script>
			(function() {
				var root = document.getElementById(<?php echo wp_json_encode( $uid ); ?>);
				if (!root) {
					return;
				}

				root.querySelectorAll('[data-extensao-modal-trigger]').forEach(function(trigger) {
					trigger.addEventListener('click', function() {
						var modalId = trigger.getAttribute('data-extensao-modal-trigger');
						var modal = document.getElementById(modalId);
						if (modal && typeof modal.showModal === 'function') {
							modal.showModal();
						}
					});
				});

				root.querySelectorAll('.fisica-extensao-modal').forEach(function(modal) {
					modal.addEventListener('click', function(event) {
						if (event.target === modal) {
							modal.close();
						}
					});

					modal.querySelectorAll('[data-extensao-modal-close]').forEach(function(button) {
						button.addEventListener('click', function() {
							modal.close();
						});
					});
				});
			}());
		</script>
		<?php

		return ob_get_clean();
	}
}
add_shortcode( 'extensao_projetos_excel', 'shortcode_extensao_projetos_excel' );
