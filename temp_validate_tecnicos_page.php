<?php
$html = file_get_contents('http://localhost/fisica/index.php/tecnicos/?v=20260506e');
$start = strpos($html, '<div data-elementor-type="wp-page" data-elementor-id="509"');
$end = strpos($html, '</footer>', $start);
$segment = ($start !== false && $end !== false) ? substr($html, $start, $end - $start) : $html;

$names = [
    'ALEX ALVES DE SOUZA',
    'ALEXANDRE ADAO IBARROLA',
    'BRUNO MARTINS DE PINHO',
    'CÁSSIA CUSTÓDIO OLIVEIRA DE ALMEIDA',
    'DAVI SCHIAVINI JARDIM',
    'EDUARDO AZEVEDO REVOREDO',
    'FLAVIA REGINA DUTRA DA SILVA',
    'GUILHERME SOUZA DE MEDEIROS',
    'LUANA NASCIMENTO MAROUVO',
    'LUCIANA BENTO MAGALHÃES',
    'MARCELO INÁCIO DE OLIVEIRA ALVES',
    'MARCO HELENIO DE PAULA ALVES COELHO',
    'RAMON SILVA DOS SANTOS',
    'RANNA MARTINS GOMES DA SILVEIRA',
    'RAPHAEL ANDRADE MATTOS',
    'RENAN BERNARDO VALADÃO',
    'SAMIR OLIVEIRA DA SILVA WANDERLEY',
    'THIAGO ZANCO SOUTO',
    'VITOR DOS SANTOS FARIAS',
    'DOUGLAS MILANEZ MARQUES',
];

$found = 0;
foreach ($names as $name) {
    if (strpos($segment, $name) !== false) {
        $found++;
    }
}

echo 'HAS_TABLE=' . (strpos($segment, 'fisica-docentes-table') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'HAS_IMAGE=' . (strpos($segment, '<img') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'FOUND_NAMES=' . $found . PHP_EOL;
echo 'HAS_DATE=' . (strpos($segment, '29/04/2026') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'HAS_MENU=' . (strpos($html, 'href="http://localhost/fisica/index.php/tecnicos/" class = "hfe-sub-menu-item hfe-sub-menu-item-active"') !== false ? 'yes' : 'no') . PHP_EOL;
