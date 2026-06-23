<?php
require 'wp-load.php';
$page = get_page_by_path('corpo-docente');
$cache = get_post_meta($page->ID, '_elementor_element_cache', true);
echo 'CACHE_REBUILT=' . (!empty($cache) ? 'YES' : 'NO') . PHP_EOL;
