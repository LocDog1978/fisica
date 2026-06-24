<?php
require __DIR__ . '/wp-load.php';

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

foreach ( $slugs as $slug ) {
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	if ( ! $post instanceof WP_Post ) {
		echo "{$slug}|NOT_FOUND\n";
		continue;
	}

	$data = get_post_meta( $post->ID, '_elementor_data', true );
	echo "{$slug}|{$post->ID}|{$post->post_title}";
	echo '|sidebar=' . substr_count( $data, 'fisica-detail-page__sidebar' );
	echo '|cards=' . substr_count( $data, 'fisica-detail-page__sidebar-card' );
	echo '|links=' . substr_count( $data, 'fisica-detail-page__sidebar-link' );
	echo '|wrap=' . substr_count( $data, 'fisica-detail-page__wrap' );
	echo PHP_EOL;

	$decoded = json_decode( $data, true );
	$walk    = static function ( $element ) use ( &$walk ) {
		if (
			is_array( $element )
			&& 'html' === ( $element['widgetType'] ?? '' )
			&& isset( $element['settings']['html'] )
		) {
			$html = $element['settings']['html'];
			if ( preg_match( '#<aside class="fisica-detail-page__sidebar">(.*?)</aside>#s', $html, $match ) ) {
				echo '  ' . trim( preg_replace( '/\s+/', ' ', $match[1] ) ) . PHP_EOL;
			}
		}
		foreach ( $element['elements'] ?? [] as $child ) {
			$walk( $child );
		}
	};
	foreach ( $decoded ?: [] as $element ) {
		$walk( $element );
	}
}
