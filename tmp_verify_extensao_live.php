<?php
$html = file_get_contents('http://localhost/fisica/index.php/extensao/?v=20260619-2');
$checks = [
  'Projetos de Extensão do Instituto de Física' => 'TITLE',
  'fisica-detail-page__sidebar' => 'SIDEBAR',
  'quadrado-servico quadrado-servico--button' => 'CARDS',
  'fisica-extensao-modal"' => 'MODALS',
  'Projetos cadastrados' => 'OLD_SIDEBAR_1',
  'Como consultar' => 'OLD_SIDEBAR_2'
];
foreach ($checks as $needle => $label) {
  if (in_array($label, ['CARDS','MODALS'], true)) {
    echo $label . '=' . substr_count($html, $needle) . PHP_EOL;
  } else {
    echo $label . '=' . (strpos($html, $needle) !== false ? '1' : '0') . PHP_EOL;
  }
}
$lines = explode("\n", $html);
for ($i = 168; $i < 190 && $i < count($lines); $i++) {
  echo $lines[$i] . PHP_EOL;
}
