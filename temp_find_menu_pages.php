<?php
require 'wp-load.php';
$keywords = ['linha', 'tempo', 'plano', 'desenvolvimento', 'gradu', 'pós', 'pos', 'extens'];
$pages = get_posts([
  'post_type' => 'page',
  'post_status' => ['publish','draft','private'],
  'posts_per_page' => -1,
  'orderby' => 'title',
  'order' => 'ASC',
]);
foreach ($pages as $p) {
  $hay = remove_accents(mb_strtolower($p->post_title . ' ' . $p->post_name));
  foreach ($keywords as $kw) {
    if (strpos($hay, remove_accents($kw)) !== false) {
      echo 'PAGE|' . $p->ID . '|title=' . $p->post_title . '|slug=' . $p->post_name . '|status=' . $p->post_status . '|link=' . get_permalink($p->ID) . PHP_EOL;
      break;
    }
  }
}
