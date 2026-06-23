<?php
require __DIR__ . '/wp-load.php';
$post = get_post(654);
echo 'MODIFIED=' . $post->post_modified . PHP_EOL;
echo 'GUID=' . $post->guid . PHP_EOL;
echo 'STATUS=' . $post->post_status . PHP_EOL;
echo 'TYPE=' . $post->post_type . PHP_EOL;
