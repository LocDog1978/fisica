<?php
require __DIR__ . '/wp-load.php';
global $wpdb;
$sql = "SELECT post_id, meta_key FROM {$wpdb->postmeta} WHERE meta_value LIKE '%fisica-home-carousel__track%' AND (meta_value LIKE '%calendario-academico%' OR meta_value LIKE '%recepcao-dos-estudantes-2026-1%' OR meta_value LIKE '%visita-tecnica-inpe-2026%')";
$rows = $wpdb->get_results($sql, ARRAY_A);
echo wp_json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
