<?php
require __DIR__ . '/wp-load.php';

$post_id    = 654;
$image_url  = 'http://localhost/fisica/wp-content/uploads/2026/06/GEO_3920-2.jpg';
$image_alt  = 'Galeria do Laboratório de Física Moderna';
$anchor_tag = '<a class="fisica-gallery-item" href="' . $image_url . '"><img src="' . $image_url . '" alt="' . $image_alt . '"></a>';

$data = get_post_meta( $post_id, '_elementor_data', true );

if ( ! is_string( $data ) || '' === $data ) {
	fwrite( STDERR, "Elementor data not found.\n" );
	exit( 1 );
}

if ( false === strpos( $data, $image_url ) ) {
	$needle = '<\/a><\/div><\/div><\/section>';
	$count  = 0;
	$data   = str_replace(
		$needle,
		'<\/a>' . $anchor_tag . '<\/div><\/div><\/section>',
		$data,
		$count
	);

	if ( 1 !== $count ) {
		fwrite( STDERR, "Gallery closing marker not found exactly once.\n" );
		exit( 1 );
	}

	update_post_meta( $post_id, '_elementor_data', wp_slash( $data ) );
	delete_post_meta( $post_id, '_elementor_element_cache' );
	clean_post_cache( $post_id );
}

$post = get_post( $post_id );
if ( $post instanceof WP_Post && is_string( $post->post_content ) && false === strpos( $post->post_content, $image_url ) ) {
	$post_content = str_replace(
		'</a></section>',
		'</a>' . $anchor_tag . '</section>',
		$post->post_content,
		$count
	);

	if ( 1 === $count ) {
		wp_update_post(
			[
				'ID'           => $post_id,
				'post_content' => $post_content,
			]
		);
	}
}

$attachment_id = attachment_url_to_postid( $image_url );
if ( $attachment_id ) {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$file     = get_attached_file( $attachment_id );
	$metadata = wp_generate_attachment_metadata( $attachment_id, $file );
	if ( is_array( $metadata ) && ! empty( $metadata ) ) {
		wp_update_attachment_metadata( $attachment_id, $metadata );
	}
	clean_attachment_cache( $attachment_id );
}

echo "UPDATED\n";
