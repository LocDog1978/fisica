<?php
require 'wp-load.php';
$attachments = get_posts([
  'post_type' => 'attachment',
  'post_status' => 'inherit',
  'posts_per_page' => 3,
  'orderby' => 'date',
  'order' => 'DESC',
]);
foreach ($attachments as $a) {
  echo $a->ID . "|" . $a->post_title . "|" . $a->post_date . "|" . get_attached_file($a->ID) . PHP_EOL;
}
