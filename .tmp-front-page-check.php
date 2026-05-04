<?php
require __DIR__ . '/wp-load.php';
$page_on_front = (int) get_option('page_on_front');
$show_on_front = get_option('show_on_front');
$post = get_post($page_on_front);
$data = $page_on_front ? get_post_meta($page_on_front, '_elementor_data', true) : '';
$result = [
  'show_on_front' => $show_on_front,
  'page_on_front' => $page_on_front,
  'front_title' => $post ? $post->post_title : '',
  'contains_old' => strpos((string) $data, 'http://localhost/fisica/noticia/inpe') !== false,
  'contains_new' => strpos((string) $data, 'http://localhost/fisica/index.php/visita-tecnica-inpe-2026/') !== false,
];
echo wp_json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
