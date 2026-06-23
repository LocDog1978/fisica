<?php
require __DIR__ . '/wp-load.php';
$post = get_post(654);
echo $post->post_content;
