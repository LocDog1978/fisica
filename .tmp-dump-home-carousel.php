<?php
require __DIR__ . '/wp-load.php';
$home = get_post(9);
$data = get_post_meta(9, '_elementor_data', true);
echo "POST_CONTENT_START\n";
echo substr((string) $home->post_content, max(0, strpos((string) $home->post_content, 'fisica-home-carousel__track') - 150), 2500);
echo "\nPOST_CONTENT_END\nMETA_START\n";
echo substr((string) $data, max(0, strpos((string) $data, 'fisica-home-carousel__track') - 150), 2500);
echo "\nMETA_END\n";
