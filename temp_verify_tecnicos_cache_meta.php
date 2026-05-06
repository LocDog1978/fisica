<?php
require 'wp-load.php';
$cache = get_post_meta(509, '_elementor_element_cache', true);
echo 'CACHE_PRESENT=' . ($cache ? 'yes' : 'no') . PHP_EOL;
if ($cache) {
  echo 'CACHE_HAS_TABLE=' . (strpos($cache, 'fisica-docentes-table') !== false ? 'yes' : 'no') . PHP_EOL;
}
