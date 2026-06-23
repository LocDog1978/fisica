<?php
require __DIR__ . '/wp-load.php';
$post = get_post(654);
echo 'POST_CONTENT_A=' . substr_count($post->post_content, '<a') . PHP_EOL;
echo 'ELEMENTOR_A=' . substr_count(get_post_meta(654, '_elementor_data', true), 'fisica-gallery-item') . PHP_EOL;
