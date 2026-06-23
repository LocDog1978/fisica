<?php
require 'wp-load.php';
$p = get_page_by_path('corpo-docente');
if ($p) {
    echo (strpos($p->post_content, 'https://buscatextual.cnpq.br/buscatextual/visualizacv.do') !== false ? 'HAS_BUSCATEXTUAL' : 'NO_BUSCATEXTUAL') . PHP_EOL;
}
