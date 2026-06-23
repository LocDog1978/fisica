<?php
require __DIR__ . '/wp-load.php';
$post = get_post(654);
echo 'LEN=' . strlen($post->post_content) . PHP_EOL;
echo 'COUNT=' . substr_count($post->post_content, '<a ') . PHP_EOL;
