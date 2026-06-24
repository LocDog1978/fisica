<?php
require __DIR__ . '/wp-load.php';

global $wpdb;

function sprint3_transform_html_widget( array &$elements, string $required_text, callable $transform ): int {
	$matches = 0;

	$walk = static function ( &$element ) use ( &$walk, &$matches, $required_text, $transform ) {
		if ( ! is_array( $element ) ) {
			return;
		}

		if (
			'html' === ( $element['widgetType'] ?? '' )
			&& isset( $element['settings']['html'] )
			&& is_string( $element['settings']['html'] )
			&& str_contains( $element['settings']['html'], $required_text )
		) {
			$element['settings']['html'] = $transform( $element['settings']['html'] );
			++$matches;
		}

		if ( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
			foreach ( $element['elements'] as &$child ) {
				$walk( $child );
			}
			unset( $child );
		}
	};

	foreach ( $elements as &$element ) {
		$walk( $element );
	}
	unset( $element );

	return $matches;
}

function sprint3_clean_docentes( string $html ): string {
	$hero_start = strpos( $html, '<header class="fisica-docentes-hero">' );
	if ( false === $hero_start ) {
		throw new RuntimeException( 'Docentes hero not found.' );
	}

	$hero_end = strpos( $html, '</header>', $hero_start );
	if ( false === $hero_end ) {
		throw new RuntimeException( 'Docentes hero end not found.' );
	}

	$hero        = substr( $html, $hero_start, $hero_end + 9 - $hero_start );
	$clean_hero  = preg_replace( '#\s*<p>.*?</p>#s', '', $hero, 1, $paragraph_count );
	$already_clean_hero = 0 === $paragraph_count;
	if ( ! $already_clean_hero && 1 !== $paragraph_count ) {
		throw new RuntimeException( 'Unexpected Docentes hero paragraph count.' );
	}
	$html = substr_replace( $html, $clean_hero, $hero_start, strlen( $hero ) );

	$overview_start = strpos( $html, '<section class="fisica-docentes-overview" aria-label="Resumo do corpo docente">' );
	if ( false !== $overview_start ) {
		$overview_end = strpos( $html, '</section>', $overview_start );
		if ( false === $overview_end ) {
			throw new RuntimeException( 'Docentes overview end not found.' );
		}
		$html = substr_replace( $html, '', $overview_start, $overview_end + 10 - $overview_start );
	} elseif ( ! $already_clean_hero ) {
		throw new RuntimeException( 'Docentes overview not found.' );
	}

	return $html;
}

function sprint3_persist( int $post_id, string $required_text, callable $transform ): void {
	global $wpdb;

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		throw new RuntimeException( "Post {$post_id} not found." );
	}

	$meta_row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_elementor_data' ORDER BY meta_id DESC LIMIT 1",
			$post_id
		)
	);
	if ( ! $meta_row ) {
		throw new RuntimeException( "Elementor data for post {$post_id} not found." );
	}

	$elements = json_decode( $meta_row->meta_value, true );
	if ( ! is_array( $elements ) ) {
		throw new RuntimeException( "Invalid Elementor JSON for post {$post_id}." );
	}

	$widget_count = sprint3_transform_html_widget( $elements, $required_text, $transform );
	if ( 1 !== $widget_count ) {
		throw new RuntimeException( "Expected one target widget for post {$post_id}; found {$widget_count}." );
	}

	$post_html = $transform( $post->post_content );
	$encoded   = wp_json_encode( $elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( false === $encoded ) {
		throw new RuntimeException( "Could not encode post {$post_id}." );
	}

	$backup_dir = WP_CONTENT_DIR . '/uploads/elementor-db-backups';
	if ( ! is_dir( $backup_dir ) && ! wp_mkdir_p( $backup_dir ) ) {
		throw new RuntimeException( 'Could not create backup directory.' );
	}
	file_put_contents(
		sprintf( '%s/sprint3-final-%d-%s.json', $backup_dir, $post_id, gmdate( 'Ymd-His' ) ),
		$meta_row->meta_value
	);

	$meta_updated = $wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->postmeta} SET meta_value = %s WHERE meta_id = %d",
			$encoded,
			(int) $meta_row->meta_id
		)
	);
	if ( false === $meta_updated ) {
		throw new RuntimeException( "Direct Elementor update failed for post {$post_id}: {$wpdb->last_error}" );
	}

	$stored_meta = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_id = %d",
			(int) $meta_row->meta_id
		)
	);
	if ( $stored_meta !== $encoded ) {
		throw new RuntimeException(
			"Elementor readback mismatch for post {$post_id}; target clean="
			. substr_count( $encoded, 'fisica-opportunity-page' )
			. ', stored clean='
			. substr_count( (string) $stored_meta, 'fisica-opportunity-page' )
		);
	}

	$post_updated = $wpdb->update(
		$wpdb->posts,
		[ 'post_content' => $post_html ],
		[ 'ID' => $post_id ],
		[ '%s' ],
		[ '%d' ]
	);
	if ( false === $post_updated ) {
		throw new RuntimeException( "Direct post update failed for post {$post_id}." );
	}

	$wpdb->delete( $wpdb->postmeta, [ 'post_id' => $post_id, 'meta_key' => '_elementor_element_cache' ], [ '%d', '%s' ] );
	$wpdb->delete( $wpdb->postmeta, [ 'post_id' => $post_id, 'meta_key' => '_elementor_css' ], [ '%d', '%s' ] );
	$wpdb->delete( $wpdb->postmeta, [ 'post_id' => $post_id, 'meta_key' => '_elementor_page_assets' ], [ '%d', '%s' ] );
	clean_post_cache( $post_id );

	echo "FINALIZED {$post_id} {$post->post_title}\n";
}

$opportunity_pages = [
	989 => 'Iniciação Científica',
	991 => 'Monitorias',
	993 => 'Estágios',
];

foreach ( $opportunity_pages as $post_id => $title ) {
	$target = '<section class="fisica-detail-page fisica-opportunity-page">'
		. '<div class="fisica-detail-page__hero">'
		. '<div class="fisica-detail-page__hero-card">'
		. '<span class="fisica-detail-page__eyebrow">Oportunidades Acadêmicas</span>'
		. '<h1 class="fisica-detail-page__title">' . esc_html( $title ) . '</h1>'
		. '</div></div></section>';

	sprint3_persist(
		$post_id,
		$title,
		static fn() => $target
	);
}

sprint3_persist(
	295,
	'Corpo Docente',
	'sprint3_clean_docentes'
);

wp_cache_flush();
echo "SPRINT 3 FINALIZED\n";
