<?php
require __DIR__ . '/wp-load.php';
$data = (string) get_post_meta(1057, '_elementor_data', true);
echo wp_json_encode([
  'has_recepcao' => strpos($data, 'recepcao-dos-estudantes-2026-1') !== false,
  'has_visita' => strpos($data, 'visita-tecnica-inpe-2026') !== false,
  'has_calendar' => strpos($data, 'calendario-academico') !== false,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
