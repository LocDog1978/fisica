<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
$attachments = get_posts([
  'post_type' => 'attachment',
  'post_status' => 'inherit',
  'post_mime_type' => 'image',
  'posts_per_page' => 5,
  'orderby' => 'date',
  'order' => 'DESC',
]);
foreach ($attachments as $a) {
  echo $a->ID . '|' . $a->post_date . '|' . $a->post_title . '|' . wp_get_attachment_url($a->ID) . PHP_EOL;
}
