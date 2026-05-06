<?php
require 'wp-load.php';
$locations = get_nav_menu_locations();
foreach ($locations as $loc => $menu_id) {
  $menu = wp_get_nav_menu_object($menu_id);
  echo 'LOCATION=' . $loc . '|MENU_ID=' . $menu_id . '|MENU_NAME=' . ($menu ? $menu->name : '') . PHP_EOL;
}
if (!empty($locations)) {
  $menu_id = reset($locations);
  $items = wp_get_nav_menu_items($menu_id, ['post_status' => 'any']);
  foreach ($items as $item) {
    echo 'ITEM|' . $item->ID . '|parent=' . $item->menu_item_parent . '|title=' . $item->title . '|url=' . $item->url . '|object=' . $item->object . '|object_id=' . $item->object_id . PHP_EOL;
  }
}
