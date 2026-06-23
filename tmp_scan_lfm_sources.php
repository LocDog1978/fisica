<?php
require __DIR__ . '/wp-load.php';
global $wpdb;
$like_old = '%GEO_2669-2.jpg%';
$like_new = '%GEO_3920-2.jpg%';
$meta = $wpdb->get_results($wpdb->prepare("SELECT meta_id, post_id, meta_key, LENGTH(meta_value) AS len FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_value LIKE %s", 654, $like_old));
foreach ($meta as $row) {
  echo "META|{$row->meta_id}|{$row->meta_key}|{$row->len}\n";
}
$meta_new = $wpdb->get_results($wpdb->prepare("SELECT meta_id, post_id, meta_key, LENGTH(meta_value) AS len FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_value LIKE %s", 654, $like_new));
foreach ($meta_new as $row) {
  echo "NEWMETA|{$row->meta_id}|{$row->meta_key}|{$row->len}\n";
}
$post = $wpdb->get_row($wpdb->prepare("SELECT ID, post_content, post_excerpt FROM {$wpdb->posts} WHERE ID = %d", 654));
echo 'POST_CONTENT_HAS_OLD=' . (strpos($post->post_content, 'GEO_2669-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'POST_CONTENT_HAS_NEW=' . (strpos($post->post_content, 'GEO_3920-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'EXCERPT_HAS_OLD=' . (strpos($post->post_excerpt, 'GEO_2669-2.jpg') !== false ? '1' : '0') . PHP_EOL;
