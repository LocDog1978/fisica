<?php
require __DIR__ . '/wp-load.php';
if (function_exists('wp_cache_flush')) {
  var_export(wp_cache_flush());
  echo PHP_EOL;
}
clean_post_cache(654);
if (function_exists('wp_opcache_invalidate')) {
  @wp_opcache_invalidate();
}
echo 'DONE';
