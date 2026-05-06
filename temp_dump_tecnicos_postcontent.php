<?php
require 'wp-load.php';
$post = get_post(509);
echo substr($post->post_content, 0, 1200);
