<?php
require 'wp-load.php';
$page = get_page_by_path('corpo-docente');
$meta = get_post_meta($page->ID, '_elementor_data', true);
$pos = strpos($meta, 'Marcia Begalli');
if ($pos !== false) {
    $start = max(0, $pos - 900);
    echo substr($meta, $start, 2600);
}
