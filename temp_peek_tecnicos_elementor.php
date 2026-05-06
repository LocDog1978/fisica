<?php
require 'wp-load.php';
$data = get_post_meta(509, '_elementor_data', true);
echo is_string($data) ? substr($data, 0, 800) : 'not-string';
