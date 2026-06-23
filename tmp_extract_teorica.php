<?php
require 'wp-load.php';
$p = get_page_by_path('corpo-docente');
if (!$p) { exit(1); }
$content = $p->post_content;
$start = strpos($content, '<h2>Física Teórica</h2>');
if ($start === false) { $start = strpos($content, '<h2>Fisica Teorica</h2>'); }
if ($start !== false) {
  echo substr($content, $start, 4500);
}
