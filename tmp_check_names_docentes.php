<?php
require 'wp-load.php';
$p = get_page_by_path('corpo-docente');
$content = $p ? $p->post_content : '';
foreach (['Marcia Begalli','Marisilvia Donadelli','Maurício Thiel','Kevin Mota'] as $name) {
    echo $name . '=' . (strpos($content, $name) !== false ? 'YES' : 'NO') . PHP_EOL;
}
