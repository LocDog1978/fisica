<?php
require __DIR__ . '/wp-load.php';
$post = get_post(1056);
echo wp_json_encode([
  'exists' => (bool) $post,
  'type' => $post ? $post->post_type : '',
  'status' => $post ? $post->post_status : '',
  'parent' => $post ? (int) $post->post_parent : 0,
  'title' => $post ? $post->post_title : '',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
