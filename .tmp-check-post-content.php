<?php
require __DIR__ . '/wp-load.php';
$post = get_post(9);
echo wp_json_encode([
  'content_has_old' => strpos((string) $post->post_content, 'http://localhost/fisica/noticia/inpe') !== false,
  'content_has_homehtml1' => strpos((string) $post->post_content, 'homehtml1') !== false,
  'excerpt' => $post->post_excerpt,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
