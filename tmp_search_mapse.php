<?php
require 'wp-load.php';
global $wpdb;
$like = '%mapse%';
$queries = [
  'posts' => $wpdb->prepare("SELECT ID, post_type, post_title FROM {$wpdb->posts} WHERE post_title LIKE %s OR post_content LIKE %s LIMIT 20", $like, $like),
  'postmeta' => $wpdb->prepare("SELECT post_id, meta_key FROM {$wpdb->postmeta} WHERE meta_value LIKE %s LIMIT 20", $like),
  'users' => $wpdb->prepare("SELECT ID, display_name, user_email FROM {$wpdb->users} WHERE display_name LIKE %s OR user_email LIKE %s LIMIT 20", $like, $like),
  'usermeta' => $wpdb->prepare("SELECT user_id, meta_key FROM {$wpdb->usermeta} WHERE meta_value LIKE %s LIMIT 20", $like),
];
foreach ($queries as $label => $sql) {
  echo "== $label ==\n";
  $rows = $wpdb->get_results($sql, ARRAY_A);
  if (!$rows) { echo "(none)\n"; continue; }
  foreach ($rows as $row) { echo wp_json_encode($row, JSON_UNESCAPED_UNICODE) . "\n"; }
}
