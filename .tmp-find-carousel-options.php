<?php
require __DIR__ . '/wp-load.php';
global $wpdb;
$sql = "SELECT option_name FROM {$wpdb->options} WHERE option_value LIKE '%fisica-home-carousel__track%' OR option_value LIKE '%calendario-academico%' LIMIT 50";
$rows = $wpdb->get_results($sql, ARRAY_A);
echo wp_json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
