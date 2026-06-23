<?php
foreach (['fotos-lfm','laboratorio-de-fisica-moderna','galeria-laboratorio-de-fisica-moderna'] as $slug) {
  $html = @file_get_contents('http://localhost/fisica/index.php/' . $slug . '/');
  echo $slug . '=' . ($html !== false ? '1' : '0') . PHP_EOL;
}
