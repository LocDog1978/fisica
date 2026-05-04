<?php
require __DIR__ . '/wp-load.php';
$ids = get_posts([
  'post_type' => 'attachment',
  'post_mime_type' => 'image',
  'post_status' => 'inherit',
  'posts_per_page' => 5,
  'orderby' => 'date',
  'order' => 'DESC',
  'fields' => 'ids',
]);
$urls = array_map('wp_get_attachment_url', $ids);
echo wp_json_encode(['ids' => array_map('intval', $ids), 'urls' => $urls], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
