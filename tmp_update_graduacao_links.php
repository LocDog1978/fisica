<?php
require __DIR__ . '/wp-load.php';

$post_id = 1094;

$replacements = [
	[
		'old' => '<a href="#">Fluxograma</a><br /><a href="#">Ementas</a>',
		'new' => '<a href="https://www.dep.uerj.br/fluxos/fisica_bacharelado.pdf">Fluxograma</a><br /><a href="https://www.ementario.uerj.br">Ementas</a>',
	],
	[
		'old' => '<a href="#">Fluxograma</a><br /><a href="#">Ementas</a>',
		'new' => '<a href="https://www.dep.uerj.br/fluxos/fisica_licenciatura.pdf">Fluxograma</a><br /><a href="https://www.ementario.uerj.br/">Ementas</a>',
	],
];

$post = get_post( $post_id );
if ( ! $post instanceof WP_Post ) {
	fwrite( STDERR, "Post not found.\n" );
	exit( 1 );
}

$post_content = $post->post_content;
$first_pos    = strpos( $post_content, $replacements[0]['old'] );
$second_pos   = strpos( $post_content, $replacements[1]['old'], $first_pos + 1 );

if ( false === $first_pos || false === $second_pos ) {
	fwrite( STDERR, "Expected link block not found in post_content.\n" );
	exit( 1 );
}

$updated_post_content = substr_replace(
	$post_content,
	$replacements[0]['new'],
	$first_pos,
	strlen( $replacements[0]['old'] )
);
$offset_adjustment     = strlen( $replacements[0]['new'] ) - strlen( $replacements[0]['old'] );
$updated_post_content  = substr_replace(
	$updated_post_content,
	$replacements[1]['new'],
	$second_pos + $offset_adjustment,
	strlen( $replacements[1]['old'] )
);

$elementor_data = get_post_meta( $post_id, '_elementor_data', true );
if ( ! is_string( $elementor_data ) || '' === $elementor_data ) {
	fwrite( STDERR, "Elementor data not found.\n" );
	exit( 1 );
}

$elementor_old_1 = '<a class=\"fisica-graduacao-card__button\" href=\"#\">Fluxograma<\/a><br \/><a class=\"fisica-graduacao-card__button\" href=\"#\">Ementas<\/a>';
$elementor_new_1 = '<a class=\"fisica-graduacao-card__button\" href=\"https:\/\/www.dep.uerj.br\/fluxos\/fisica_bacharelado.pdf\">Fluxograma<\/a><br \/><a class=\"fisica-graduacao-card__button\" href=\"https:\/\/www.ementario.uerj.br\">Ementas<\/a>';
$elementor_old_2 = '<a class=\"fisica-graduacao-card__button\" href=\"#\">Fluxograma<\/a><br \/><a class=\"fisica-graduacao-card__button\" href=\"#\">Ementas<\/a>';
$elementor_new_2 = '<a class=\"fisica-graduacao-card__button\" href=\"https:\/\/www.dep.uerj.br\/fluxos\/fisica_licenciatura.pdf\">Fluxograma<\/a><br \/><a class=\"fisica-graduacao-card__button\" href=\"https:\/\/www.ementario.uerj.br\/\">Ementas<\/a>';

$first_meta_pos  = strpos( $elementor_data, $elementor_old_1 );
$second_meta_pos = strpos( $elementor_data, $elementor_old_2, $first_meta_pos + 1 );

if ( false === $first_meta_pos || false === $second_meta_pos ) {
	fwrite( STDERR, "Expected link block not found in Elementor data.\n" );
	exit( 1 );
}

$updated_elementor = substr_replace(
	$elementor_data,
	$elementor_new_1,
	$first_meta_pos,
	strlen( $elementor_old_1 )
);
$meta_offset       = strlen( $elementor_new_1 ) - strlen( $elementor_old_1 );
$updated_elementor = substr_replace(
	$updated_elementor,
	$elementor_new_2,
	$second_meta_pos + $meta_offset,
	strlen( $elementor_old_2 )
);

wp_update_post(
	[
		'ID'           => $post_id,
		'post_content' => $updated_post_content,
	]
);

update_post_meta( $post_id, '_elementor_data', wp_slash( $updated_elementor ) );
delete_post_meta( $post_id, '_elementor_element_cache' );
clean_post_cache( $post_id );
wp_cache_flush();

echo "UPDATED\n";
