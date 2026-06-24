<?php
require __DIR__ . '/wp-load.php';

global $wpdb;

$slugs = [
	'gravitacao',
	'ensino-de-fisico',
	'materia-condensada-experimental',
	'altas-energias',
	'teoria-quantica',
	'matematica',
	'ciencias-biomedicas-ambientais',
	'aplicacoes-industriais',
];

function sprint4_clean_sidebar( string $html ): string {
	$pattern = '~(<aside\b[^>]*>).*?(<a\b[^>]*href="[^"]*#programas"[^>]*>\s*Voltar para pesquisa\s*</a>).*?</aside>~s';

	$updated = preg_replace(
		$pattern,
		'$1$2</aside>',
		$html,
		1,
		$count
	);

	if ( 1 !== $count ) {
		throw new RuntimeException( "Expected one research sidebar; found {$count}." );
	}

	return $updated;
}

function sprint4_transform_widget( array &$elements ): int {
	$matches = 0;

	$walk = static function ( &$element ) use ( &$walk, &$matches ) {
		if ( ! is_array( $element ) ) {
			return;
		}

		if (
			'html' === ( $element['widgetType'] ?? '' )
			&& isset( $element['settings']['html'] )
			&& is_string( $element['settings']['html'] )
			&& str_contains( $element['settings']['html'], 'fisica-detail-page__sidebar' )
		) {
			$element['settings']['html'] = sprint4_clean_sidebar( $element['settings']['html'] );
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

$backup_dir = WP_CONTENT_DIR . '/uploads/elementor-db-backups';
if ( ! is_dir( $backup_dir ) && ! wp_mkdir_p( $backup_dir ) ) {
	throw new RuntimeException( 'Could not create backup directory.' );
}

foreach ( $slugs as $slug ) {
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	if ( ! $post instanceof WP_Post ) {
		throw new RuntimeException( "Research page {$slug} not found." );
	}

	$meta_row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_elementor_data' ORDER BY meta_id DESC LIMIT 1",
			$post->ID
		)
	);
	if ( ! $meta_row ) {
		throw new RuntimeException( "Elementor data for {$slug} not found." );
	}

	$elements = json_decode( $meta_row->meta_value, true );
	if ( ! is_array( $elements ) ) {
		throw new RuntimeException( "Invalid Elementor JSON for {$slug}." );
	}

	if ( 1 !== sprint4_transform_widget( $elements ) ) {
		throw new RuntimeException( "Expected one target HTML widget for {$slug}." );
	}

	$updated_content = sprint4_clean_sidebar( $post->post_content );
	$encoded         = wp_json_encode( $elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( false === $encoded ) {
		throw new RuntimeException( "Could not encode Elementor data for {$slug}." );
	}

	$backup_file = sprintf(
		'%s/sprint4-research-%d-%s.json',
		$backup_dir,
		$post->ID,
		gmdate( 'Ymd-His' )
	);
	if ( false === file_put_contents( $backup_file, $meta_row->meta_value ) ) {
		throw new RuntimeException( "Could not back up {$slug}." );
	}

	$meta_updated = $wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->postmeta} SET meta_value = %s WHERE meta_id = %d",
			$encoded,
			(int) $meta_row->meta_id
		)
	);
	if ( false === $meta_updated ) {
		throw new RuntimeException( "Could not update Elementor data for {$slug}: {$wpdb->last_error}" );
	}

	$post_updated = $wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->posts} SET post_content = %s WHERE ID = %d",
			$updated_content,
			$post->ID
		)
	);
	if ( false === $post_updated ) {
		throw new RuntimeException( "Could not update post content for {$slug}: {$wpdb->last_error}" );
	}

	$stored_meta = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_id = %d",
			(int) $meta_row->meta_id
		)
	);
	if (
		1 !== substr_count( (string) $stored_meta, 'fisica-detail-page__sidebar-link' )
		|| 0 !== substr_count( (string) $stored_meta, 'fisica-detail-page__sidebar-card' )
	) {
		throw new RuntimeException( "Elementor readback validation failed for {$slug}." );
	}

	$wpdb->delete( $wpdb->postmeta, [ 'post_id' => $post->ID, 'meta_key' => '_elementor_element_cache' ], [ '%d', '%s' ] );
	$wpdb->delete( $wpdb->postmeta, [ 'post_id' => $post->ID, 'meta_key' => '_elementor_css' ], [ '%d', '%s' ] );
	$wpdb->delete( $wpdb->postmeta, [ 'post_id' => $post->ID, 'meta_key' => '_elementor_page_assets' ], [ '%d', '%s' ] );
	clean_post_cache( $post->ID );

	echo "UPDATED {$post->ID} {$slug}\n";
}

wp_cache_flush();
echo "SPRINT 4 RESEARCH UPDATED\n";
