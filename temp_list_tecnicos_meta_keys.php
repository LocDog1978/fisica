<?php
require 'wp-load.php';
global $wpdb;
$rows = $wpdb->get_results($wpdb->prepare("SELECT meta_key FROM {$wpdb->postmeta} WHERE post_id = %d ORDER BY meta_id ASC", 509), ARRAY_A);
foreach ($rows as $row) {
  echo $row['meta_key'] . PHP_EOL;
}
