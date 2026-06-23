<?php
require __DIR__ . '/wp-load.php';
$pages = get_posts([
  'post_type' => 'page',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'orderby' => 'ID',
  'order' => 'ASC',
]);
foreach ($pages as $page) {
  if (stripos($page->post_title, 'Extensão') !== false || stripos($page->post_title, 'Extensao') !== false || stripos($page->post_title, 'Área do Aluno') !== false || stripos($page->post_title, 'Area do Aluno') !== false) {
    echo $page->ID . '|' . $page->post_name . '|' . $page->post_title . PHP_EOL;
  }
}
