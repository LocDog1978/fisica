<?php
require 'wp-load.php';
global $wpdb;
$rows = $wpdb->get_results("SELECT meta_key, LEFT(meta_value, 400) AS snippet FROM {$wpdb->postmeta} WHERE post_id = 509 ORDER BY meta_id ASC", ARRAY_A);
foreach ($rows as $row) {
  if (strpos($row['snippet'], 'tecnicoshtmlv1') !== false || strpos($row['snippet'], 'tecnicoshtmlv2') !== false || strpos($row['snippet'], 'Esta página foi ajustada') !== false || strpos($row['snippet'], 'Servidores Técnicos') !== false) {
    echo $row['meta_key'] . '|' . $row['snippet'] . PHP_EOL . PHP_EOL;
  }
}
