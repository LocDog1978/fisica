<?php
require 'wp-load.php';
$meta = get_post_meta(509, '_elementor_data', true);
$post = get_post(509);
echo 'meta_v2=' . (strpos($meta, 'tecnicoshtmlv2') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'post_v2=' . (strpos($post->post_content, 'tecnicoshtmlv2') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'post_has_table=' . (strpos($post->post_content, 'fisica-docentes-table') !== false ? 'yes' : 'no') . PHP_EOL;
