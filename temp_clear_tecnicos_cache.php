<?php
require 'wp-load.php';
wp_cache_flush();
if ( class_exists('\Elementor\Plugin') ) {
  $plugin = \Elementor\Plugin::$instance;
  if ( isset($plugin->files_manager) ) {
    $plugin->files_manager->clear_cache();
  }
}
delete_transient('elementor_css_file_509');
delete_post_meta(509, '_elementor_css');
echo "cleared" . PHP_EOL;
