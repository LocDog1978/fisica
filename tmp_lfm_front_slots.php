<?php
$html = file_get_contents('http://localhost/fisica/index.php/fotos-lfm/');
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);
$items = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' fisica-gallery-item ')]");
echo 'ITEMS=' . $items->length . PHP_EOL;
$index = 0;
foreach ($items as $item) {
  $index++;
  $img = $xpath->query('.//img', $item)->item(0);
  echo $index . '|' . basename($item->getAttribute('href')) . '|src=' . basename($img ? $img->getAttribute('src') : '') . '|w=' . ($img ? $img->getAttribute('width') : '') . '|h=' . ($img ? $img->getAttribute('height') : '') . PHP_EOL;
}
