<?php
$html = file_get_contents(__DIR__ . '/tmp_fotos_lfm_front.html');
if (!preg_match('/<section>(.*?)<\/section>/s', $html, $m)) {
    echo "NO_SECTION\n";
    exit;
}
$section = $m[1];
echo 'SECTION_ANCHORS=' . substr_count($section, '<a') . PHP_EOL;
echo 'SECTION_HAS_NEW=' . (strpos($section, 'GEO_3920-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'SECTION_HAS_OLD_LAST=' . (strpos($section, 'GEO_2669-2.jpg') !== false ? '1' : '0') . PHP_EOL;
