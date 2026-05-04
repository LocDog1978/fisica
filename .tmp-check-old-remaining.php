<?php
require __DIR__ . '/wp-load.php';
global $wpdb;
$rows = $wpdb->get_results("SELECT post_id, meta_key FROM {$wpdb->postmeta} WHERE meta_value LIKE '%http://localhost/fisica/noticia/inpe%' LIMIT 20", ARRAY_A);
echo wp_json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
