<?php
require __DIR__ . '/wp-load.php';
foreach ([1056, 1057] as $id) {
  $data = (string) get_post_meta($id, '_elementor_data', true);
  $rows[] = [
    'id' => $id,
    'has_recepcao' => strpos($data, 'recepcao-dos-estudantes-2026-1') !== false,
    'has_visita' => strpos($data, 'visita-tecnica-inpe-2026') !== false,
    'has_calendar' => strpos($data, 'calendario-academico') !== false,
    'slide_count' => preg_match_all('/fisica-home-carousel__slide/', $data),
  ];
}
echo wp_json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
