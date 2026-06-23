<?php
require __DIR__ . '/wp-load.php';
$post = get_post(1094);
echo $post->post_content;
