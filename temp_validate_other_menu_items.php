<?php
require 'wp-load.php';
$items = wp_get_nav_menu_items(3, ['post_status' => 'any']);
foreach ($items as $item) {
  if (!in_array($item->ID, [24,305,1088,1089,1090,1091,1092,1093], true) && in_array($item->title, ['Departamentos','Área do Aluno','Pessoas','Contato','Bolsas'], true)) {
    echo $item->ID . '|title=' . $item->title . '|parent=' . $item->menu_item_parent . '|position=' . $item->menu_order . PHP_EOL;
  }
}
