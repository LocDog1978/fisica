<?php
$html = file_get_contents(__DIR__ . '/tmp_fotos_lfm_front.html');
echo 'HAS_NEW=' . (strpos($html, 'GEO_3920-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'OLD_COUNT=' . substr_count($html, 'GEO_2669-2.jpg') . PHP_EOL;
echo 'UPLOAD_ANCHORS=' . substr_count($html, '<a href="http://localhost/fisica/wp-content/uploads/') . PHP_EOL;
