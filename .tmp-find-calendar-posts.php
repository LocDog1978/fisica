<?php
require __DIR__ . '/wp-load.php';
global $wpdb;
$posts = $wpdb->get_results("SELECT ID, post_type, post_status, post_parent, post_title FROM {$wpdb->posts} WHERE post_content LIKE '%calendario-academico%' LIMIT 50", ARRAY_A);
echo wp_json_encode($posts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
