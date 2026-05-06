<?php
require 'wp-load.php';
$targets = ['Sobre o Instituto','Linha do Tempo','Plano de Desenvolvimento do Instituto','Graduação','Pós Graduação','Extensão'];
foreach ($targets as $title) {
  $posts = get_posts([
    'post_type' => 'page',
    'post_status' => ['publish','draft','private'],
    'posts_per_page' => 10,
    's' => $title,
    'orderby' => 'title',
    'order' => 'ASC',
  ]);
  echo 'QUERY=' . $title . PHP_EOL;
  foreach ($posts as $p) {
    echo 'PAGE|' . $p->ID . '|title=' . $p->post_title . '|slug=' . $p->post_name . '|status=' . $p->post_status . '|link=' . get_permalink($p->ID) . PHP_EOL;
  }
}
