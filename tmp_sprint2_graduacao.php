<?php
require __DIR__ . '/wp-load.php';

$post_id = 1094;
$intro   = 'Apresentação institucional do curso de Física do Instituto de Física “Armando Dias Tavares” — IFADT/UERJ, nas modalidades Bacharelado e Licenciatura.';

$post = get_post( $post_id );
if ( ! $post instanceof WP_Post || 'Graduação' !== $post->post_title ) {
	fwrite( STDERR, "Expected Graduação page not found.\n" );
	exit( 1 );
}

$post_paragraph = '<p>' . $intro . '</p>';
if ( 1 !== substr_count( $post->post_content, $post_paragraph ) ) {
	fwrite( STDERR, "Expected one intro paragraph in post_content.\n" );
	exit( 1 );
}

$elementor_data = get_post_meta( $post_id, '_elementor_data', true );
$elements       = json_decode( $elementor_data, true );
if ( ! is_array( $elements ) ) {
	fwrite( STDERR, "Elementor data is not valid JSON.\n" );
	exit( 1 );
}

$updated_post_content = str_replace( $post_paragraph, '', $post->post_content );
$elementor_replacements = 0;

$remove_intro = static function ( &$value ) use ( &$remove_intro, &$elementor_replacements, $post_paragraph ) {
	if ( is_array( $value ) ) {
		foreach ( $value as &$child ) {
			$remove_intro( $child );
		}
		unset( $child );
		return;
	}

	if ( is_string( $value ) ) {
		$count = substr_count( $value, $post_paragraph );
		if ( $count > 0 ) {
			$value = str_replace( $post_paragraph, '', $value );
			$elementor_replacements += $count;
		}
	}
};

$remove_intro( $elements );

if ( 1 !== $elementor_replacements ) {
	fwrite( STDERR, "Expected one intro paragraph in decoded Elementor data.\n" );
	exit( 1 );
}

$updated_elementor = wp_json_encode( $elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
if ( false === $updated_elementor ) {
	fwrite( STDERR, "Could not encode updated Elementor data.\n" );
	exit( 1 );
}

$result = wp_update_post(
	[
		'ID'           => $post_id,
		'post_content' => $updated_post_content,
	],
	true
);

if ( is_wp_error( $result ) ) {
	fwrite( STDERR, $result->get_error_message() . "\n" );
	exit( 1 );
}

if ( false === update_post_meta( $post_id, '_elementor_data', wp_slash( $updated_elementor ) ) ) {
	fwrite( STDERR, "Elementor data was not updated.\n" );
	exit( 1 );
}

delete_post_meta( $post_id, '_elementor_element_cache' );
clean_post_cache( $post_id );
wp_cache_flush();

echo "UPDATED\n";
