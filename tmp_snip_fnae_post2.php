<?php
require 'wp-load.php';
$p = get_page_by_path('corpo-docente');
$content = $p ? $p->post_content : '';
$pos = strpos($content, 'Marcia Begalli');
if ($pos !== false) {
    $start = max(0, $pos - 800);
    echo substr($content, $start, 3200);
}
