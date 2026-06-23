<?php
require 'wp-load.php';
$page = get_page_by_path('corpo-docente');
if (!$page) exit(1);
echo 'CACHE_META=' . (metadata_exists('post', $page->ID, '_elementor_element_cache') ? 'YES' : 'NO') . PHP_EOL;
echo 'POST_MODIFIED=' . $page->post_modified . PHP_EOL;
