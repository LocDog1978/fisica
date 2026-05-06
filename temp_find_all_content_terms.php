<?php
require 'wp-load.php';
global $wpdb;
$terms = ['linha','tempo','plano','desenvolvimento','gradu','extens','pos'];
foreach ($terms as $term) {
  echo 'TERM=' . $term . PHP_EOL;
  $like = '%' . $wpdb->esc_like($term) . '%';
  $rows = $wpdb->get_results($wpdb->prepare("SELECT ID, post_type, post_status, post_title, post_name FROM {$wpdb->posts} WHERE (post_title LIKE %s OR post_name LIKE %s) AND post_status NOT IN ('auto-draft') ORDER BY post_type, post_title", $like, $like));
  foreach ($rows as $row) {
    echo 'POST|' . $row->ID . '|type=' . $row->post_type . '|status=' . $row->post_status . '|title=' . $row->post_title . '|slug=' . $row->post_name . PHP_EOL;
  }
}
