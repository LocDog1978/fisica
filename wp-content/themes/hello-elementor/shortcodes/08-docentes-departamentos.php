<?php
/**
 * Quantitativos dos departamentos derivados da fonte da página Corpo Docente.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'fisica_find_docentes_source_html' ) ) {
	/**
	 * Localiza o widget HTML que contém os registros da página Corpo Docente.
	 *
	 * @param array $elements Elementos decodificados do Elementor.
	 *
	 * @return string
	 */
	function fisica_find_docentes_source_html( $elements ) {
		foreach ( (array) $elements as $element ) {
			$html = $element['settings']['html'] ?? '';

			if (
				is_string( $html ) &&
				false !== strpos( $html, 'data-docentes-app' ) &&
				false !== strpos( $html, 'data-docentes-panel' )
			) {
				return $html;
			}

			if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) ) {
				$html = fisica_find_docentes_source_html( $element['elements'] );

				if ( '' !== $html ) {
					return $html;
				}
			}
		}

		return '';
	}
}

if ( ! function_exists( 'fisica_get_docentes_by_department' ) ) {
	/**
	 * Retorna os docentes agrupados pelo departamento informado na fonte.
	 *
	 * Cada linha da estrutura de dados da página Corpo Docente é convertida em
	 * um registro. Assim, os quantitativos não dependem do DOM já renderizado.
	 *
	 * @return array<string, array<int, array<string, string>>>
	 */
	function fisica_get_docentes_by_department() {
		static $docentes_by_department = null;

		if ( null !== $docentes_by_department ) {
			return $docentes_by_department;
		}

		$docentes_by_department = [];
		$page_id                 = fisica_resolve_deployed_post_id( 295, 'page' );

		if ( ! $page_id ) {
			return $docentes_by_department;
		}

		$elementor_data = json_decode( (string) get_post_meta( $page_id, '_elementor_data', true ), true );
		$source_html    = is_array( $elementor_data ) ? fisica_find_docentes_source_html( $elementor_data ) : '';

		if ( '' === $source_html || ! class_exists( 'DOMDocument' ) ) {
			return $docentes_by_department;
		}

		$document            = new DOMDocument();
		$previous_error_mode = libxml_use_internal_errors( true );
		$loaded              = $document->loadHTML(
			'<?xml encoding="utf-8" ?><div id="fisica-docentes-source">' . $source_html . '</div>',
			LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
		);
		libxml_clear_errors();
		libxml_use_internal_errors( $previous_error_mode );

		if ( ! $loaded ) {
			return $docentes_by_department;
		}

		$xpath  = new DOMXPath( $document );
		$panels = $xpath->query( '//*[@data-docentes-panel]' );

		if ( false === $panels ) {
			return $docentes_by_department;
		}

		foreach ( $panels as $panel ) {
			$department = sanitize_key( $panel->getAttribute( 'data-docentes-panel' ) );

			if ( '' === $department ) {
				continue;
			}

			$docentes_by_department[ $department ] = [];
			$rows                                      = $xpath->query( './/tbody/tr', $panel );

			if ( false === $rows ) {
				continue;
			}

			foreach ( $rows as $row ) {
				$cells = $xpath->query( './td', $row );

				if ( false === $cells || 0 === $cells->length ) {
					continue;
				}

				$record = [];
				foreach ( [ 'nome', 'area', 'sala' ] as $cell_index => $field ) {
					$record[ $field ] = $cells->length > $cell_index
						? trim( preg_replace( '/\s+/u', ' ', $cells->item( $cell_index )->textContent ) )
						: '';
				}

				$lattes_link      = $xpath->query( './/a[@href]', $row );
				$record['lattes'] = false !== $lattes_link && $lattes_link->length
					? (string) $lattes_link->item( 0 )->getAttribute( 'href' )
					: '';

				$docentes_by_department[ $department ][] = $record;
			}
		}

		return $docentes_by_department;
	}
}

if ( ! function_exists( 'fisica_docentes_count_shortcode' ) ) {
	/**
	 * Exibe o total atual de docentes vinculados a um departamento.
	 *
	 * @param array $attributes Atributos do shortcode.
	 *
	 * @return string
	 */
	function fisica_docentes_count_shortcode( $attributes ) {
		$attributes = shortcode_atts(
			[
				'departamento' => '',
			],
			$attributes,
			'fisica_docentes_count'
		);

		$department = sanitize_key( $attributes['departamento'] );
		$docentes    = fisica_get_docentes_by_department();

		if ( '' === $department || ! array_key_exists( $department, $docentes ) ) {
			return '';
		}

		return esc_html( (string) count( $docentes[ $department ] ) );
	}
}
add_shortcode( 'fisica_docentes_count', 'fisica_docentes_count_shortcode' );
