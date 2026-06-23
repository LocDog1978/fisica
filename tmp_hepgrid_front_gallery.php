<?php
$html = file_get_contents('http://localhost/fisica/index.php/fotos-hepgrid/');
if ($html === false) { echo "FETCH_FAIL\n"; exit(1); }
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);
$items = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' fisica-gallery-item ')]");
echo 'ITEMS=' . $items->length . PHP_EOL;
foreach ($items as $item) {
  $href = $item->getAttribute('href');
  $img = $xpath->query('.//img', $item)->item(0);
  echo basename($href) . '|' . basename($img ? $img->getAttribute('src') : '') . PHP_EOL;
}
