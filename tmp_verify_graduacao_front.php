<?php
$html = file_get_contents(__DIR__ . '/tmp_graduacao_front.html');
$checks = [
  'https://www.dep.uerj.br/fluxos/fisica_bacharelado.pdf',
  'https://www.ementario.uerj.br',
  'https://www.dep.uerj.br/fluxos/fisica_licenciatura.pdf',
  'https://www.ementario.uerj.br/'
];
foreach ($checks as $url) {
  echo $url . '=' . (strpos($html, $url) !== false ? '1' : '0') . PHP_EOL;
}
echo 'HASH_COUNT=' . substr_count($html, 'href="#"') . PHP_EOL;
