<?php
require 'wp-load.php';
foreach (['_elementor_page_assets','_elementor_element_cache'] as $key) {
  $value = get_post_meta(509, $key, true);
  echo $key . '|' . (is_string($value) ? substr($value, 0, 400) : json_encode($value)) . PHP_EOL . PHP_EOL;
}
