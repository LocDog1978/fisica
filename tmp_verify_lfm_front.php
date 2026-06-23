<?php
$html = file_get_contents('http://localhost/fisica/index.php/fotos-lfm/');
if ($html === false) {
    fwrite(STDERR, "FAILED\n");
    exit(1);
}
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);
$items = $xpath->query("//div[contains(concat(' ', normalize-space(@class), ' '), ' fisica-gallery-grid ')]//a[contains(concat(' ', normalize-space(@class), ' '), ' fisica-gallery-item ')]");
echo 'ITEMS=' . $items->length . PHP_EOL;
foreach ($items as $i => $item) {
    $img = $xpath->query('.//img', $item)->item(0);
    echo ($i + 1) . '|' . $item->getAttribute('href') . '|' . ($img ? $img->getAttribute('src') : 'NOIMG') . PHP_EOL;
}
