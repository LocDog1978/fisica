<?php
require 'wp-load.php';
$page = get_page_by_path('corpo-docente');
if (!$page) exit(1);
$keys = [
  '_elementor_data',
  '_elementor_edit_mode',
  '_elementor_element_cache',
  '_elementor_page_assets',
  '_elementor_controls_usage',
  '_elementor_css',
  '_elementor_version'
];
foreach ($keys as $key) {
  $value = get_post_meta($page->ID, $key, true);
  if ($value === '') {
    echo $key . "=EMPTY\n";
  } else {
    echo $key . '=' . (is_string($value) ? substr(str_replace(["\r","\n"], [' ',' '], $value), 0, 180) : gettype($value)) . "\n";
  }
}
