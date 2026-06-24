<?php
require __DIR__ . '/wp-load.php';

$post = get_page_by_path( 'gravitacao', OBJECT, 'page' );
if ( ! $post instanceof WP_Post ) {
	exit( 1 );
}

if ( preg_match( '#<aside class="fisica-detail-page__sidebar".*?</aside>#s', $post->post_content, $match ) ) {
	echo $match[0] . PHP_EOL;
} else {
	echo "NO_ASIDE\n";
	echo substr( $post->post_content, -1200 ) . PHP_EOL;
}
