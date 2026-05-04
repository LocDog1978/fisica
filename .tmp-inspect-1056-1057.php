<?php
require __DIR__ . '/wp-load.php';
foreach ([1056,1057] as $id) {
  echo "=== {$id} data ===\n";
  $data = (string) get_post_meta($id, '_elementor_data', true);
  $start = strpos($data, 'fisica-home-carousel__track');
  echo false === $start ? "not-found\n" : substr($data, max(0, $start - 120), 2200) . "\n";
  echo "=== {$id} cache ===\n";
  $cache = (string) get_post_meta($id, '_elementor_element_cache', true);
  $start = strpos($cache, 'fisica-home-carousel__track');
  echo false === $start ? "not-found\n" : substr($cache, max(0, $start - 120), 2200) . "\n";
}
