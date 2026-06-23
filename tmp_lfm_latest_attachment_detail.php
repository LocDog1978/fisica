<?php
require __DIR__ . '/wp-load.php';
$attachment = get_posts([
  'post_type' => 'attachment',
  'post_status' => 'inherit',
  'posts_per_page' => 1,
  'orderby' => 'date',
  'order' => 'DESC',
]);
if (!$attachment) { echo "NO_ATTACHMENT\n"; exit; }
$a = $attachment[0];
echo 'ID=' . $a->ID . PHP_EOL;
echo 'TITLE=' . $a->post_title . PHP_EOL;
echo 'URL=' . wp_get_attachment_url($a->ID) . PHP_EOL;
echo 'IMG=' . wp_get_attachment_image($a->ID, 'large', false, ['alt' => 'Galeria do Laboratório de Física Moderna']) . PHP_EOL;
$meta = wp_get_attachment_metadata($a->ID);
echo 'META=' . json_encode($meta, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
