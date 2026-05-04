<?php
require __DIR__ . '/wp-load.php';
$data = (string) get_post_meta(9, '_elementor_data', true);
$start = strpos($data, 'http://localhost/fisica/index.php/calendario-academico/');
if (false === $start) {
  echo "calendar-not-found\n";
} else {
  echo substr($data, max(0, $start - 250), 1200);
}
