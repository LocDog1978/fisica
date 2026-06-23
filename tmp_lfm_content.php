<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
$post = get_post(654);
echo 'TITLE=' . $post->post_title . PHP_EOL;
echo 'COUNT=' . substr_count((string)$post->post_content, 'fisica-gallery-item') . PHP_EOL;
$raw = $GLOBALS['wpdb']->get_var("SELECT post_content FROM {$GLOBALS['wpdb']->posts} WHERE ID = 654");
echo 'RAW_COUNT=' . substr_count($raw, 'fisica-gallery-item') . PHP_EOL;
$pos = strpos($raw, 'fisica-gallery-grid');
if ($pos !== false) echo substr($raw, $pos, 3000);
