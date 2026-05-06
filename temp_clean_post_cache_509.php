<?php
require 'wp-load.php';
clean_post_cache(509);
wp_cache_delete(509, 'posts');
wp_cache_delete(509, 'post_meta');
wp_cache_flush();
echo 'cleaned_post_cache' . PHP_EOL;
