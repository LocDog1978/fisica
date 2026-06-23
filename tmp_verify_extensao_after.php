<?php
$html = file_get_contents(__DIR__ . '/tmp_extensao_after.html');
$checks = [
  'fisica-detail-page',
  'fisica-detail-page__hero',
  'fisica-detail-page__panel',
  'fisica-detail-page__sidebar',
  'quadrado-servico',
  'fisica-extensao-modal'
];
foreach ($checks as $check) {
  echo $check . '=' . (strpos($html, $check) !== false ? '1' : '0') . PHP_EOL;
}
echo 'CARDS=' . substr_count($html, 'quadrado-servico quadrado-servico--button') . PHP_EOL;
echo 'MODALS=' . substr_count($html, 'fisica-extensao-modal"') . PHP_EOL;
