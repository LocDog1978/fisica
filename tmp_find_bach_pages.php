<?php
require __DIR__ . '/wp-load.php';
$pages = get_posts([
  'post_type' => ['page'],
  'post_status' => 'publish',
  'posts_per_page' => -1,
  's' => 'Bacharelado'
]);
foreach ($pages as $page) {
  echo $page->ID . '|' . $page->post_name . '|' . $page->post_title . PHP_EOL;
}
