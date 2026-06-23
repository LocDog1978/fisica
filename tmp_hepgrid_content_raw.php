<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
$raw = $GLOBALS['wpdb']->get_var("SELECT post_content FROM {$GLOBALS['wpdb']->posts} WHERE ID = 656");
$pos = strpos($raw, 'fisica-gallery-grid');
if ($pos !== false) {
  echo substr($raw, $pos, 2600);
}
