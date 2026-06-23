<?php
require __DIR__ . '/wp-load.php';
global $wpdb;
$old = '%GEO_2669-2.jpg%';
$rows = $wpdb->get_results($wpdb->prepare("SELECT option_id, option_name, LENGTH(option_value) AS len FROM {$wpdb->options} WHERE option_value LIKE %s ORDER BY option_id DESC LIMIT 50", $old));
foreach ($rows as $row) {
  echo "OPT|{$row->option_id}|{$row->option_name}|{$row->len}\n";
}
