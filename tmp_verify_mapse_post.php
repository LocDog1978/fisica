<?php
require 'wp-load.php';
$page = get_page_by_path('corpo-docente');
$content = $page ? $page->post_content : '';
foreach (['Mapse Barroso Ferreira Filho','Gilson Correia Silva','Cesar Augusto Linhares da Fonseca Junior'] as $name) {
    echo $name . '=' . (strpos($content, $name) !== false ? 'YES' : 'NO') . PHP_EOL;
}
$posMapse = strpos($content, 'Mapse Barroso Ferreira Filho');
$posMarcia = strpos($content, 'Marcia Begalli');
$posMarisilvia = strpos($content, 'Marisilvia Donadelli');
echo 'ORDER=' . (($posMarcia !== false && $posMapse !== false && $posMarisilvia !== false && $posMarcia < $posMapse && $posMapse < $posMarisilvia) ? 'OK' : 'CHECK') . PHP_EOL;
