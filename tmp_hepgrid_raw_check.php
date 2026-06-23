<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
$raw = $GLOBALS['wpdb']->get_var("SELECT post_content FROM {$GLOBALS['wpdb']->posts} WHERE ID = 656");
echo 'RAW_HAS_5476=' . (strpos($raw, 'GEO_5476-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'RAW_HAS_6491=' . (strpos($raw, 'GEO_6491-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'RAW_COUNT=' . substr_count($raw, 'fisica-gallery-item') . PHP_EOL;
echo substr($raw, -900);
