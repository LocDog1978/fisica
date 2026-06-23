<?php
$files = [
'C:/xampp/htdocs/fisica/wp-content/uploads/2026/06/GEO_5476-2.jpg',
'C:/xampp/htdocs/fisica/wp-content/uploads/2026/06/GEO_6491-2.jpg'
];
foreach ($files as $path) {
  $info = getimagesize($path);
  echo basename($path) . '|' . $info[0] . 'x' . $info[1] . '|' . filesize($path) . PHP_EOL;
}
