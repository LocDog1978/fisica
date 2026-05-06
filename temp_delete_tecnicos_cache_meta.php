<?php
require 'wp-load.php';
delete_post_meta(509, '_elementor_page_assets');
delete_post_meta(509, '_elementor_element_cache');
clean_post_cache(509);
wp_cache_flush();
echo 'deleted_cache_meta' . PHP_EOL;
