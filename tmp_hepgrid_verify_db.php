<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
$content = (string)get_post(656)->post_content;
echo 'POST_HAS_5476=' . (strpos($content, 'GEO_5476-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'POST_HAS_6491=' . (strpos($content, 'GEO_6491-2.jpg') !== false ? '1' : '0') . PHP_EOL;
$meta = get_post_meta(656, '_elementor_data', true);
echo 'META_HAS_5476=' . (strpos($meta, 'GEO_5476-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'META_HAS_6491=' . (strpos($meta, 'GEO_6491-2.jpg') !== false ? '1' : '0') . PHP_EOL;
