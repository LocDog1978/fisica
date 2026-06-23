<?php
require __DIR__ . '/wp-load.php';
$post_id = 654;
$data = get_post_meta($post_id, '_elementor_data', true);
if (!is_string($data)) {
    echo "NO_STRING\n";
    exit;
}
echo substr($data, 0, 12000);
