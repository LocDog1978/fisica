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

if ( ! function_exists( 'fisica_extensao_uppercase_text' ) ) {
	/**
	 * Uppercase text with multibyte support when available.
	 *
	 * @param string $text Source text.
	 *
	 * @return string
	 */
	function fisica_extensao_uppercase_text( $text ) {
		$text = (string) $text;

		if ( function_exists( 'mb_strtoupper' ) ) {
			return mb_strtoupper( $text, 'UTF-8' );
		}

		return strtoupper( $text );
	}
}

if ( ! function_exists( 'fisica_extensao_fix_text' ) ) {
	/**
	 * Apply targeted orthographic fixes without changing the meaning.
	 *
	 * @param string $text Source text.
	 *
	 * @return string
	 */
	function fisica_extensao_fix_text( $text ) {
		return strtr(
			(string) $text,
			[
				'mídias sócias'       => 'mídias sociais',
				'Instituto de Fídica' => 'Instituto de Física',
				'o numero'            => 'o número',
				'da analise'          => 'da análise',
			]
		);
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
				'title'       => fisica_extensao_fix_text( $cells['A'] ),
				'email'       => fisica_extensao_fix_text( $cells['B'] ),
				'coordinator' => fisica_extensao_fix_text( $cells['C'] ),
				'summary'     => fisica_extensao_fix_text( $cells['D'] ),
			];
		}

		$cache = array_slice( $cache, 0, 17 );

		return $cache;
	}
}

if ( ! function_exists( 'fisica_get_latest_media_image_url' ) ) {
	/**
	 * Return the latest uploaded image URL from the WordPress media library.
	 *
	 * @return string
	 */
	function fisica_get_latest_media_image_url() {
		static $latest_image_url = null;

		if ( null !== $latest_image_url ) {
			return $latest_image_url;
		}

		$latest_image_url = '';

		$attachments = get_posts(
			[
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'post_mime_type' => 'image',
				'posts_per_page' => 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
			]
		);

		if ( ! empty( $attachments ) ) {
			$latest_image_url = (string) wp_get_attachment_url( (int) $attachments[0] );
		}

		return $latest_image_url;
	}
}

if ( ! function_exists( 'fisica_get_second_latest_media_image_url' ) ) {
	/**
	 * Return the second latest uploaded image URL from the WordPress media library.
	 *
	 * @return string
	 */
	function fisica_get_second_latest_media_image_url() {
		static $second_latest_image_url = null;

		if ( null !== $second_latest_image_url ) {
			return $second_latest_image_url;
		}

		$second_latest_image_url = '';

		$attachments = get_posts(
			[
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'post_mime_type' => 'image',
				'posts_per_page' => 2,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
			]
		);

		if ( isset( $attachments[1] ) ) {
			$second_latest_image_url = (string) wp_get_attachment_url( (int) $attachments[1] );
		}

		return $second_latest_image_url;
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
		$title    = fisica_extensao_uppercase_text( $project['title'] );
		$classes  = 'quadrado-servico quadrado-servico--button';
		$style    = '';

		if ( 'COM CIÊNCIA FÍSICA' === $title ) {
			$latest_image_url = (string) wp_get_attachment_url( 1290 );

			if ( '' !== $latest_image_url ) {
				$classes .= ' quadrado-servico--featured-bg';
				$style    = sprintf(
					' style="%s"',
					esc_attr(
						sprintf(
							'--fisica-extensao-card-bg-image: url(%s);',
							esc_url_raw( $latest_image_url )
						)
					)
				);
			}
		}

		if ( 'GALILEOMOBILE RIO DE ESTRELAS' === $title ) {
			// $second_latest_image_url = fisica_get_second_latest_media_image_url();
			$upload_dir              = wp_upload_dir();
			$second_latest_image_url = trailingslashit( $upload_dir['baseurl'] ) . '2026/07/galileomobile_desktop_1600x1000.jpg';

			if ( '' !== $second_latest_image_url ) {
				$classes .= ' quadrado-servico--galileomobile-bg';
				$style    = sprintf(
					' style="%s"',
					esc_attr(
						sprintf(
							'--fisica-extensao-card-bg-image: url(%s);',
							esc_url_raw( $second_latest_image_url )
						)
					)
				);
			}
		}

		if ( 11 === (int) $index ) {
			$upload_dir          = wp_upload_dir();
			$project_image_url   = trailingslashit( $upload_dir['baseurl'] ) . '2026/07/ippog_masterclass_desktop_1600x1000.jpg';
			$classes            .= ' quadrado-servico--galileomobile-bg';
			$style               = sprintf(
				' style="%s"',
				esc_attr(
					sprintf(
						'--fisica-extensao-card-bg-image: url(%s);',
						esc_url_raw( $project_image_url )
					)
				)
			);
		}

		if ( 'A FÍSICA NA MÚSICA' === $title ) {
			$upload_dir        = wp_upload_dir();
			$project_image_url = trailingslashit( $upload_dir['baseurl'] ) . '2026/08/ChatGPT-Image-11-de-ago.-de-2026-23_37_14.png';
			$classes          .= ' quadrado-servico--galileomobile-bg quadrado-servico--fisica-musica-bg';
			$style             = sprintf(
				' style="%s"',
				esc_attr(
					sprintf(
						'--fisica-extensao-card-bg-image: url(%s);',
						esc_url_raw( $project_image_url )
					)
				)
			);
		}

		if ( 10 === (int) $index ) {
			$upload_dir        = wp_upload_dir();
			$project_image_url = trailingslashit( $upload_dir['baseurl'] ) . '2026/08/ChatGPT-Image-6-de-ago.-de-2026-16_54_36.png';
			$classes          .= ' quadrado-servico--galileomobile-bg';
			$style             = sprintf(
				' style="%s"',
				esc_attr(
					sprintf(
						'--fisica-extensao-card-bg-image: url(%s);',
						esc_url_raw( $project_image_url )
					)
				)
			);
		}

		if ( 0 === strpos( $title, 'CONSTRUINDO PONTES:' ) ) {
			$upload_dir        = wp_upload_dir();
			$project_image_url = trailingslashit( $upload_dir['baseurl'] ) . '2026/08/ChatGPT-Image-6-de-ago.-de-2026-16_15_34.png';
			$classes          .= ' quadrado-servico--galileomobile-bg';
			$style             = sprintf(
				' style="%s"',
				esc_attr(
					sprintf(
						'--fisica-extensao-card-bg-image: url(%s);',
						esc_url_raw( $project_image_url )
					)
				)
			);
		}

		return sprintf(
			'<button type="button" class="%1$s" data-extensao-modal-trigger="%2$s" aria-controls="%2$s" aria-label="%3$s"%4$s><span class="quadrado-conteudo"><span class="quadrado-servico__indice">%5$s</span><h3>%3$s</h3></span></button>',
			esc_attr( $classes ),
			esc_attr( $modal_id ),
			esc_html( $title ),
			$style,
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
		$title      = fisica_extensao_uppercase_text( $project['title'] );

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
			esc_html( $title ),
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

		$uid        = 'fisica-extensao-' . wp_generate_uuid4();
		$page_title = 'Projetos de Extensão do Instituto de Física';

		ob_start();
		?>
		<section class="fisica-detail-page fisica-extensao-page" id="<?php echo esc_attr( $uid ); ?>" aria-labelledby="<?php echo esc_attr( $uid ); ?>-title">
			<div class="fisica-detail-page__hero">
				<div class="fisica-detail-page__hero-card">
					<span class="fisica-detail-page__eyebrow">Oportunidades Acadêmicas</span>
					<h1 class="fisica-detail-page__title" id="<?php echo esc_attr( $uid ); ?>-title"><?php echo esc_html( $page_title ); ?></h1>
				</div>
			</div>

			<style>
				.fisica-extensao-page .fisica-detail-page__wrap {
					grid-template-columns: minmax(0, 1fr);
				}

				.fisica-extensao-page .grid-quadrados-servicos {
					max-width: none;
					margin: 0;
					grid-template-columns: repeat(3, minmax(0, 1fr));
				}

				.fisica-extensao-page .quadrado-servico--featured-bg {
					background-image:
						linear-gradient(180deg, rgba(0, 0, 0, 0.15) 0%, rgba(0, 0, 0, 0.15) 100%),
						var(--fisica-extensao-card-bg-image);
					background-position: center center, center center;
					background-repeat: no-repeat, no-repeat;
					background-size: cover, cover;
				}

				.fisica-extensao-page .quadrado-servico--featured-bg::before {
					background:
						linear-gradient(145deg, rgba(15, 76, 129, 0.01), rgba(80, 162, 255, 0.08)),
						radial-gradient(circle at top right, rgba(80, 162, 255, 0.08), transparent 36%);
					opacity: 1;
				}

				.fisica-extensao-page .quadrado-servico--featured-bg .quadrado-conteudo,
				.fisica-extensao-page .quadrado-servico--featured-bg .quadrado-servico__indice,
				.fisica-extensao-page .quadrado-servico--featured-bg h3 {
					position: relative;
					z-index: 2;
				}

				.fisica-extensao-page .quadrado-servico--featured-bg h3 {
					color: #ffffff;
					text-shadow:
						0 1px 2px rgba(0, 0, 0, 0.95),
						0 2px 5px rgba(0, 0, 0, 0.75);
				}

				.fisica-extensao-page .quadrado-servico--featured-bg .quadrado-servico__indice {
					color: #ffffff;
					text-shadow:
						0 1px 2px rgba(0, 0, 0, 0.95),
						0 2px 5px rgba(0, 0, 0, 0.75);
					background: rgba(0, 0, 0, 0.18);
				}

				.fisica-extensao-page .quadrado-servico--galileomobile-bg {
					background-image:
						linear-gradient(
							rgba(0, 0, 0, 0.20),
							rgba(0, 0, 0, 0.20)
						),
						var(--fisica-extensao-card-bg-image);
					background-position: center center;
					background-repeat: no-repeat;
					background-size: cover;
				}

				.fisica-extensao-page .quadrado-servico--fisica-musica-bg {
					background-image:
						linear-gradient(rgba(0, 0, 0, 0.20), rgba(0, 0, 0, 0.20)),
						var(--fisica-extensao-card-bg-image),
						var(--fisica-extensao-card-bg-image);
					background-position: center, center, center;
					background-repeat: no-repeat;
					background-size: cover, contain, cover;
				}

				.fisica-extensao-page .quadrado-servico--galileomobile-bg::before {
					background: rgba(0, 0, 0, 0.20);
					opacity: 1;
				}

				.fisica-extensao-page .quadrado-servico--galileomobile-bg .quadrado-conteudo,
				.fisica-extensao-page .quadrado-servico--galileomobile-bg .quadrado-servico__indice,
				.fisica-extensao-page .quadrado-servico--galileomobile-bg h3 {
					position: relative;
					z-index: 2;
				}

				.fisica-extensao-page .quadrado-servico--galileomobile-bg h3 {
					color: #ffffff;
					text-shadow:
						0 1px 2px rgba(0, 0, 0, 0.95),
						0 2px 5px rgba(0, 0, 0, 0.75);
				}

				.fisica-extensao-page .quadrado-servico--galileomobile-bg .quadrado-servico__indice {
					color: #ffffff;
					text-shadow:
						0 1px 2px rgba(0, 0, 0, 0.95),
						0 2px 5px rgba(0, 0, 0, 0.75);
					background: rgba(0, 0, 0, 0.18);
				}

				@media (max-width: 1024px) {
					.fisica-extensao-page .grid-quadrados-servicos {
						grid-template-columns: repeat(2, minmax(0, 1fr));
					}
				}

				@media (max-width: 767px) {
					.fisica-extensao-page .grid-quadrados-servicos {
						grid-template-columns: minmax(0, 1fr);
					}
				}
			</style>

			<div class="fisica-detail-page__wrap">
				<div class="fisica-detail-page__content">
					<div class="fisica-detail-page__panel">
						<section class="fisica-detail-page__section">
							<div class="fisica-programas fisica-extensao-programas">
								<div class="fisica-programas__header">
									<span class="fisica-programas__eyebrow">Extensão</span>
								</div>

								<div class="grid-quadrados-servicos">
									<?php foreach ( $projects as $index => $project ) : ?>
										<?php echo fisica_render_extensao_project_card( $project, $index + 1, $uid ); ?>
									<?php endforeach; ?>
								</div>
							</div>
						</section>
					</div>
				</div>

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
