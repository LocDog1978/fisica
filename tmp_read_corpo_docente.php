<?php
require 'wp-load.php';
$p = get_page_by_path('corpo-docente');
if ($p) {
    echo 'ID=' . $p->ID . PHP_EOL;
    echo 'POST_CONTENT=' . substr($p->post_content, 0, 500) . PHP_EOL;
}
