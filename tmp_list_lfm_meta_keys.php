<?php
require __DIR__ . '/wp-load.php';
$keys = get_post_meta(654);
foreach ($keys as $key => $values) {
    $value = is_array($values) ? (string) reset($values) : (string) $values;
    if (strpos($value, 'GEO_2669-2.jpg') !== false || strpos($value, 'GEO_3920-2.jpg') !== false || strpos($value, 'fisica-gallery-grid') !== false) {
        echo $key . '|' . strlen($value) . PHP_EOL;
    }
}
