<?php
require __DIR__ . '/wp-load.php';
$pages = get_posts([
  'post_type' => 'page',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'orderby' => 'title',
  'order' => 'ASC',
]);
foreach ($pages as $page) {
  $title = $page->post_title;
  $slug = $page->post_name;
  if (stripos($title, 'Extens') !== false || stripos($title, 'Aluno') !== false || stripos($slug, 'aluno') !== false || stripos($slug, 'extens') !== false) {
    echo $page->ID . '|' . $slug . '|' . $title . PHP_EOL;
  }
}
