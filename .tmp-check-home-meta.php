<?php
require __DIR__ . '/wp-load.php';
$data = get_post_meta(9, '_elementor_data', true);
$result = [
  'contains_old' => strpos($data, 'http://localhost/fisica/noticia/inpe') !== false,
  'contains_new' => strpos($data, 'http://localhost/fisica/index.php/visita-tecnica-inpe-2026/') !== false,
  'length' => strlen((string) $data),
];
echo wp_json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
