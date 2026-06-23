<?php
require __DIR__ . '/wp-load.php';
$data = get_post_meta(654, '_elementor_data', true);
echo substr($data, -1200);
