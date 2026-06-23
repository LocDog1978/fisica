<?php
$html = file_get_contents('http://localhost/fisica/index.php/fotos-hepgrid/');
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);
$items = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' fisica-gallery-item ')]");
echo 'ITEMS=' . $items->length . PHP_EOL;
foreach ($items as $item) {
  $href = $item->getAttribute('href');
  $img = $xpath->query('.//img', $item)->item(0);
  echo basename($href) . '|src=' . basename($img ? $img->getAttribute('src') : '') . '|w=' . ($img ? $img->getAttribute('width') : '') . '|h=' . ($img ? $img->getAttribute('height') : '') . PHP_EOL;
}
echo 'HAS_5476=' . (strpos($html, 'GEO_5476-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'HAS_6491=' . (strpos($html, 'GEO_6491-2.jpg') !== false ? '1' : '0') . PHP_EOL;
