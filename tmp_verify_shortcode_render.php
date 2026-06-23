<?php
require __DIR__ . '/wp-load.php';
$content = do_shortcode('[extensao_projetos_excel]');
echo 'DETAIL=' . (strpos($content, 'fisica-detail-page') !== false ? '1' : '0') . PHP_EOL;
echo 'COUNT=' . substr_count($content, 'quadrado-servico quadrado-servico--button') . PHP_EOL;
