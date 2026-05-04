<?php
require __DIR__ . '/wp-load.php';
$post = get_post(9);
$cache = get_post_meta(9, '_elementor_element_cache', true);
$data = get_post_meta(9, '_elementor_data', true);
$result = [
  'post_content_old' => strpos((string) $post->post_content, 'http://localhost/fisica/noticia/inpe') !== false,
  'post_content_new' => strpos((string) $post->post_content, 'http://localhost/fisica/index.php/visita-tecnica-inpe-2026/') !== false,
  'cache_old' => is_string($cache) && strpos($cache, 'http://localhost/fisica/noticia/inpe') !== false,
  'cache_new' => is_string($cache) && strpos($cache, 'http://localhost/fisica/index.php/visita-tecnica-inpe-2026/') !== false,
  'data_old' => strpos((string) $data, 'http://localhost/fisica/noticia/inpe') !== false,
  'data_new' => strpos((string) $data, 'http://localhost/fisica/index.php/visita-tecnica-inpe-2026/') !== false,
];
echo wp_json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
