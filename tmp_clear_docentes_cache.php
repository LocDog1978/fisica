<?php
require 'wp-load.php';
$page = get_page_by_path('corpo-docente');
if (!$page) {
    fwrite(STDERR, "Page not found\n");
    exit(1);
}
$deleted = delete_post_meta($page->ID, '_elementor_element_cache');
echo 'DELETED=' . ($deleted ? 'YES' : 'NO') . PHP_EOL;
