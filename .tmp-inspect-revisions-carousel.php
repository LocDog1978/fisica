<?php
require __DIR__ . '/wp-load.php';
foreach ([1024,1025,1026,1057] as $id) {
  $post = get_post($id);
  $data = (string) get_post_meta($id, '_elementor_data', true);
  $cache = (string) get_post_meta($id, '_elementor_element_cache', true);
  $rows[] = [
    'id' => $id,
    'parent' => $post ? (int) $post->post_parent : 0,
    'title' => $post ? $post->post_title : '',
    'data_has_calendar' => strpos($data, 'calendario-academico') !== false,
    'data_has_recepcao' => strpos($data, 'recepcao-dos-estudantes-2026-1') !== false,
    'cache_has_calendar' => strpos($cache, 'calendario-academico') !== false,
    'cache_has_recepcao' => strpos($cache, 'recepcao-dos-estudantes-2026-1') !== false,
  ];
}
echo wp_json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
