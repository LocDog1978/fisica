<?php
require 'wp-load.php';
global $wpdb;
$terms = ['linha','tempo','plano','desenvolvimento','gradu','extens','pos','pós'];
foreach ($terms as $term) {
  echo 'TERM=' . $term . PHP_EOL;
  $like = '%' . $wpdb->esc_like($term) . '%';
  $rows = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title, post_name, post_status FROM {$wpdb->posts} WHERE post_type='page' AND (post_title LIKE %s OR post_name LIKE %s) ORDER BY post_title ASC", $like, $like));
  foreach ($rows as $row) {
    echo 'PAGE|' . $row->ID . '|title=' . $row->post_title . '|slug=' . $row->post_name . '|status=' . $row->post_status . '|link=' . get_permalink($row->ID) . PHP_EOL;
  }
}
