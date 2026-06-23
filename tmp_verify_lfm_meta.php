<?php
require __DIR__ . '/wp-load.php';
$data = get_post_meta(654, '_elementor_data', true);
echo (substr_count($data, 'fisica-gallery-item')) . PHP_EOL;
echo (strpos($data, 'GEO_3920-2.jpg') !== false ? 'FOUND' : 'MISSING') . PHP_EOL;
