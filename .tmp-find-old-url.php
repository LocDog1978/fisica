<?php
require __DIR__ . '/wp-load.php';
global $wpdb;
$postmeta = $wpdb->get_results("SELECT post_id, meta_key FROM {$wpdb->postmeta} WHERE meta_value LIKE '%http://localhost/fisica/noticia/inpe%' LIMIT 50", ARRAY_A);
$options = $wpdb->get_results("SELECT option_name FROM {$wpdb->options} WHERE option_value LIKE '%http://localhost/fisica/noticia/inpe%' LIMIT 50", ARRAY_A);
echo wp_json_encode(['postmeta' => $postmeta, 'options' => $options], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
