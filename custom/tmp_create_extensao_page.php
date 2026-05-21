<?php
require __DIR__ . '/../wp-load.php';

$page_title   = 'EXTENSÃO';
$page_slug    = 'extensao';
$page_content = '[extensao_projetos_excel]';

$existing = get_page_by_path( $page_slug, OBJECT, 'page' );

$page_data = [
	'post_title'   => $page_title,
	'post_name'    => $page_slug,
	'post_content' => $page_content,
	'post_status'  => 'publish',
	'post_type'    => 'page',
	'post_author'  => 1,
];

if ( $existing instanceof WP_Post ) {
	$page_data['ID'] = $existing->ID;
	$page_id         = wp_update_post( $page_data, true );
} else {
	$page_id = wp_insert_post( $page_data, true );
}

if ( is_wp_error( $page_id ) ) {
	fwrite( STDERR, $page_id->get_error_message() . PHP_EOL );
	exit( 1 );
}

update_post_meta( $page_id, '_wp_page_template', 'elementor_header_footer' );
update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
update_post_meta( $page_id, '_elementor_version', '3.32.1' );

$menu = wp_get_nav_menu_object( 'menu' );

if ( $menu ) {
	$items          = wp_get_nav_menu_items( $menu->term_id );
	$already_exists = false;

	foreach ( (array) $items as $item ) {
		if ( (int) $item->object_id === (int) $page_id ) {
			$already_exists = true;
			break;
		}
	}

	if ( ! $already_exists ) {
		wp_update_nav_menu_item(
			$menu->term_id,
			0,
			[
				'menu-item-title'     => 'Extensão',
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_id,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-parent-id' => 0,
			]
		);
	}
}

clean_post_cache( $page_id );
wp_cache_flush();

echo 'PAGE_ID=' . $page_id . PHP_EOL;
