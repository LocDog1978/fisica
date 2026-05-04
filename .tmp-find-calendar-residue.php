<?php
require __DIR__ . '/wp-load.php';
global $wpdb;
$rows = $wpdb->get_results("SELECT post_id, meta_key FROM {$wpdb->postmeta} WHERE meta_value LIKE '%calendario-academico%' OR meta_value LIKE '%Agenda, calend%' LIMIT 50", ARRAY_A);
echo wp_json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
