<?php
require __DIR__ . '/wp-load.php';
$home = get_post(9);
$data = get_post_meta(9, '_elementor_data', true);
$cache = get_post_meta(9, '_elementor_element_cache', true);
$fields = [
  'post_content' => (string) $home->post_content,
  '_elementor_data' => (string) $data,
  '_elementor_element_cache' => (string) $cache,
];
foreach ($fields as $key => $value) {
  $start = strpos($value, 'fisica-home-carousel__track');
  echo "=== {$key} ===\n";
  if (false === $start) {
    echo "not-found\n";
    continue;
  }
  echo substr($value, max(0, $start - 120), 2600) . "\n";
}
