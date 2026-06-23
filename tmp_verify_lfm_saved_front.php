<?php
$html = file_get_contents(__DIR__ . '/tmp_fotos_lfm_front.html');
echo 'HAS_GEO=' . (strpos($html, 'GEO_3920-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'ITEMS=' . substr_count($html, 'fisica-gallery-item') . PHP_EOL;
