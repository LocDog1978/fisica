<?php
require __DIR__ . '/wp-load.php';
$meta = wp_get_attachment_metadata(1252);
echo json_encode($meta, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
