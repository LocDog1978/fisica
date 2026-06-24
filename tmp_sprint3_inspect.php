<?php
require __DIR__ . '/wp-load.php';

global $wpdb;

foreach ( [ 989, 991, 993, 295 ] as $post_id ) {
	$post = get_post( $post_id );
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_elementor_data' ORDER BY meta_id ASC",
			$post_id
		)
	);

	echo "{$post_id}|{$post->post_title}|post_clean=" . substr_count( $post->post_content, 'fisica-opportunity-page' );
	echo '|post_wrap=' . substr_count( $post->post_content, 'fisica-detail-page__wrap' );
	echo '|post_overview=' . substr_count( $post->post_content, 'fisica-docentes-overview' );
	echo '|meta_rows=' . count( $rows ) . PHP_EOL;

	foreach ( $rows as $row ) {
		echo "  meta_id={$row->meta_id}";
		echo '|clean=' . substr_count( $row->meta_value, 'fisica-opportunity-page' );
		echo '|wrap=' . substr_count( $row->meta_value, 'fisica-detail-page__wrap' );
		echo '|overview=' . substr_count( $row->meta_value, 'fisica-docentes-overview' );
		echo '|generic=' . substr_count( $row->meta_value, 'Use as abas' );
		echo PHP_EOL;
	}
}
