<?php
require 'wp-load.php';
$menu_id = 3;
$items = wp_get_nav_menu_items($menu_id, ['post_status' => 'any']);
foreach ($items as $item) {
  echo $item->ID . '|title=' . $item->title . '|parent=' . $item->menu_item_parent . '|position=' . $item->menu_order . PHP_EOL;
}
