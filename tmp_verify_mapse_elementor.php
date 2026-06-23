<?php
require 'wp-load.php';
$page = get_page_by_path('corpo-docente');
$meta = get_post_meta($page->ID, '_elementor_data', true);
echo 'META_MAPSE=' . (strpos($meta, 'Mapse Barroso Ferreira Filho') !== false ? 'YES' : 'NO') . PHP_EOL;
echo 'CACHE_META=' . (metadata_exists('post', $page->ID, '_elementor_element_cache') ? 'YES' : 'NO') . PHP_EOL;
