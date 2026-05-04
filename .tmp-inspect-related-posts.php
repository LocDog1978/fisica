<?php
require __DIR__ . '/wp-load.php';
foreach ([1024,1025,1026,1056] as $id) {
  $post = get_post($id);
  $cache = get_post_meta($id, '_elementor_element_cache', true);
  $data = get_post_meta($id, '_elementor_data', true);
  $rows[] = [
    'id' => $id,
    'type' => $post ? $post->post_type : '',
    'parent' => $post ? (int) $post->post_parent : 0,
    'title' => $post ? $post->post_title : '',
    'data_old' => strpos((string) $data, 'http://localhost/fisica/noticia/inpe') !== false,
    'cache_old' => strpos((string) $cache, 'http://localhost/fisica/noticia/inpe') !== false,
  ];
}
echo wp_json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
