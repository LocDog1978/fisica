<?php
require 'wp-load.php';
$items = wp_get_nav_menu_items(3, ['post_status' => 'any']);
foreach ($items as $item) {
  if ((int) $item->menu_item_parent === 24 || (int) $item->menu_item_parent === 1088 || (int) $item->ID === 24) {
    echo $item->ID . '|title=' . $item->title . '|parent=' . $item->menu_item_parent . '|position=' . $item->menu_order . '|url=' . $item->url . '|type=' . $item->type . PHP_EOL;
  }
}
