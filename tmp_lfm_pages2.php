<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
$pages = get_posts([
  'post_type' => 'page',
  'posts_per_page' => -1,
  'post_status' => ['publish','draft','private'],
  's' => 'Moderna',
]);
foreach ($pages as $p) {
  echo $p->ID . '|' . $p->post_name . '|' . $p->post_title . PHP_EOL;
}
