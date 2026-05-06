<?php
require 'wp-load.php';
$menus = wp_get_nav_menus();
foreach ($menus as $menu) {
  echo 'MENU|' . $menu->term_id . '|name=' . $menu->name . PHP_EOL;
  $items = wp_get_nav_menu_items($menu->term_id, ['post_status' => 'any']);
  if (!$items) { continue; }
  foreach ($items as $item) {
    echo 'ITEM|' . $item->ID . '|menu=' . $menu->term_id . '|parent=' . $item->menu_item_parent . '|title=' . $item->title . '|url=' . $item->url . '|object=' . $item->object . '|object_id=' . $item->object_id . PHP_EOL;
  }
}
