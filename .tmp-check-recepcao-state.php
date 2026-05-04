<?php
require __DIR__ . '/wp-load.php';
$post = get_page_by_path('recepcao-dos-estudantes-2026-1', OBJECT, 'page');
$home = get_post(9);
echo wp_json_encode([
  'recepcao_page_id' => $post ? (int) $post->ID : 0,
  'recepcao_status' => $post ? $post->post_status : '',
  'home_contains_recepcao' => strpos((string) $home->post_content, 'recepcao-dos-estudantes-2026-1') !== false,
  'home_contains_visita' => strpos((string) $home->post_content, 'visita-tecnica-inpe-2026') !== false,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
