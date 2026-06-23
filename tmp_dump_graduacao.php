<?php
require __DIR__ . '/wp-load.php';
$post_id = 1094;
$post = get_post($post_id);
echo 'POST_CONTENT_LEN=' . strlen($post->post_content) . PHP_EOL;
echo $post->post_content . PHP_EOL;
$data = get_post_meta($post_id, '_elementor_data', true);
echo 'ELEMENTOR_LEN=' . (is_string($data) ? strlen($data) : 0) . PHP_EOL;
if (is_string($data)) {
  echo substr($data, 0, 15000);
}
