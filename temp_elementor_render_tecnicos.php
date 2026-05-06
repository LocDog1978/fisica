<?php
require 'wp-load.php';
if ( class_exists('\Elementor\Plugin') ) {
  $html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display(509);
  echo 'has_old=' . (strpos($html, 'Esta página foi ajustada para seguir o mesmo padrão visual') !== false ? 'yes' : 'no') . PHP_EOL;
  echo 'has_new=' . (strpos($html, 'Relação organizada em formato textual para publicação institucional') !== false ? 'yes' : 'no') . PHP_EOL;
  echo 'has_table=' . (strpos($html, 'fisica-docentes-table') !== false ? 'yes' : 'no') . PHP_EOL;
}
