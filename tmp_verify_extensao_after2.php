<?php
$html = file_get_contents(__DIR__ . '/tmp_extensao_after2.html');
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
