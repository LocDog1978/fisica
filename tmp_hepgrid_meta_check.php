<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
$meta = get_post_meta(656, '_elementor_data', true);
echo 'META_LEN=' . strlen($meta) . PHP_EOL;
echo 'HAS_6491=' . (strpos($meta, 'GEO_6491-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'HAS_5476=' . (strpos($meta, 'GEO_5476-2.jpg') !== false ? '1' : '0') . PHP_EOL;
