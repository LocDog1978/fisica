<?php
require 'wp-load.php';
$p = get_page_by_path('corpo-docente');
if (!$p) { exit(1); }
$content = $p->post_content;
$targets = [
  'Mapse Barroso Ferreira Filho',
  'Gilson Correia Silva',
  'Antonio Vilela Pereira',
  'Maria Clemencia Rosario Mora Herrera',
  'Cesar Augusto Linhares da Fonseca Junior'
];
foreach ($targets as $target) {
  echo $target . '=' . (strpos($content, $target) !== false ? 'YES' : 'NO') . PHP_EOL;
}
$start = strpos($content, '<h2>Física Nuclear e Altas Energias</h2>');
if ($start !== false) {
  echo "---SECTION---\n";
  echo substr($content, $start, 5000);
}
