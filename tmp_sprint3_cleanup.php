<?php
require __DIR__ . '/wp-load.php';

function update_elementor_html_page( int $post_id, callable $transform ): void {
	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		throw new RuntimeException( "Post {$post_id} not found." );
	}

	$elementor_json = get_post_meta( $post_id, '_elementor_data', true );
	$elements       = json_decode( $elementor_json, true );
	if ( ! is_array( $elements ) ) {
		throw new RuntimeException( "Invalid Elementor data for post {$post_id}." );
	}

	$html_widgets = 0;
	$walk         = static function ( &$element ) use ( &$walk, &$html_widgets, $transform, $post_id ) {
		if ( ! is_array( $element ) ) {
			return;
		}

		if (
			'html' === ( $element['widgetType'] ?? '' )
			&& isset( $element['settings']['html'] )
			&& is_string( $element['settings']['html'] )
		) {
			$updated = $transform( $element['settings']['html'], $post_id );
			if ( $updated !== $element['settings']['html'] ) {
				$element['settings']['html'] = $updated;
				++$html_widgets;
			}
		}

		foreach ( $element['elements'] ?? [] as &$child ) {
			$walk( $child );
		}
		unset( $child );
	};

	foreach ( $elements as &$element ) {
		$walk( $element );
	}
	unset( $element );

	if ( $html_widgets > 1 ) {
		throw new RuntimeException( "Expected at most one changed HTML widget for post {$post_id}; found {$html_widgets}." );
	}

	$updated_post_content = $transform( $post->post_content, $post_id );
	$post_content_changed = $updated_post_content !== $post->post_content;

	$encoded = wp_json_encode( $elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( false === $encoded ) {
		throw new RuntimeException( "Could not encode Elementor data for post {$post_id}." );
	}

	if ( 1 === $html_widgets ) {
		$backup_dir = WP_CONTENT_DIR . '/uploads/elementor-db-backups';
		if ( ! is_dir( $backup_dir ) && ! wp_mkdir_p( $backup_dir ) ) {
			throw new RuntimeException( 'Could not create backup directory.' );
		}

		$backup_file = sprintf(
			'%s/sprint3-%d-%s.json',
			$backup_dir,
			$post_id,
			gmdate( 'Ymd-His' )
		);
		if ( false === file_put_contents( $backup_file, $elementor_json ) ) {
			throw new RuntimeException( "Could not back up post {$post_id}." );
		}
	}

	if ( $post_content_changed ) {
		$result = wp_update_post(
			[
				'ID'           => $post_id,
				'post_content' => $updated_post_content,
			],
			true
		);
		if ( is_wp_error( $result ) ) {
			throw new RuntimeException( $result->get_error_message() );
		}
	}

	if ( 1 === $html_widgets && false === update_post_meta( $post_id, '_elementor_data', wp_slash( $encoded ) ) ) {
		$current_elements = json_decode( get_post_meta( $post_id, '_elementor_data', true ), true );
		if ( $current_elements !== $elements ) {
			throw new RuntimeException( "Could not update Elementor data for post {$post_id}." );
		}
	}

	delete_post_meta( $post_id, '_elementor_element_cache' );
	delete_post_meta( $post_id, '_elementor_css' );
	delete_post_meta( $post_id, '_elementor_page_assets' );
	clean_post_cache( $post_id );

	echo ( $html_widgets || $post_content_changed ? 'UPDATED' : 'VERIFIED' ) . " {$post_id} {$post->post_title}\n";
}

$opportunity_pages = [
	989 => 'Iniciação Científica',
	991 => 'Monitorias',
	993 => 'Estágios',
];

foreach ( $opportunity_pages as $post_id => $title ) {
	update_elementor_html_page(
		$post_id,
		static function ( string $html ) use ( $title ): string {
			if (
				1 !== substr_count( $html, '<section class="fisica-detail-page">' )
				|| 1 !== substr_count( $html, '<div class="fisica-detail-page__wrap">' )
				|| 1 !== substr_count( $html, '<h1 class="fisica-detail-page__title">' . $title . '</h1>' )
			) {
				return $html;
			}

			return '<section class="fisica-detail-page fisica-opportunity-page">'
				. '<div class="fisica-detail-page__hero">'
				. '<div class="fisica-detail-page__hero-card">'
				. '<span class="fisica-detail-page__eyebrow">Oportunidades Acadêmicas</span>'
				. '<h1 class="fisica-detail-page__title">' . esc_html( $title ) . '</h1>'
				. '</div>'
				. '</div>'
				. '</section>';
		}
	);
}

update_elementor_html_page(
	295,
	static function ( string $html ): string {
		$hero_pattern     = '#(<header class="fisica-docentes-hero">\s*<span class="fisica-docentes-eyebrow">.*?</span>\s*<h1>Corpo Docente</h1>)\s*<p>.*?</p>\s*(</header>)#s';
		$overview_pattern = '#\s*<section class="fisica-docentes-overview" aria-label="Resumo do corpo docente">.*?</section>#s';

		if (
			1 !== preg_match( $hero_pattern, $html )
			|| 1 !== preg_match( $overview_pattern, $html )
		) {
			return $html;
		}

		$html = preg_replace( $hero_pattern, '$1' . "\n  " . '$2', $html, 1, $hero_count );
		$html = preg_replace( $overview_pattern, '', $html, 1, $overview_count );

		if ( 1 !== $hero_count || 1 !== $overview_count ) {
			throw new RuntimeException( 'Could not remove the expected Docentes introduction blocks.' );
		}

		return $html;
	}
);

wp_cache_flush();
echo "SPRINT 3 UPDATED\n";
