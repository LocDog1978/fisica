<?php
require __DIR__ . '/wp-load.php';
ob_start();
$attachment_id = 1252;
if (function_exists('wp_update_image_subsizes')) {
    var_export(wp_update_image_subsizes($attachment_id));
}
$out = ob_get_clean();
echo $out === '' ? 'NO_OUTPUT' : $out;
