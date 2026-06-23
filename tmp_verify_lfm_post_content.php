<?php
require __DIR__ . '/wp-load.php';
$post = get_post(654);
echo 'COUNT=' . substr_count($post->post_content, '<a ') . PHP_EOL;
echo 'HAS_NEW=' . (strpos($post->post_content, 'GEO_3920-2.jpg') !== false ? '1' : '0') . PHP_EOL;
