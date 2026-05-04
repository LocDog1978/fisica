<?php
require __DIR__ . '/wp-load.php';
foreach (['_elementor_data','_elementor_element_cache'] as $key) {
  $value = (string) get_post_meta(9, $key, true);
  echo "=== {$key} ===\n";
  echo (strpos($value, 'recepcao-dos-estudantes-2026-1') !== false ? 'has-recepcao' : 'no-recepcao') . "\n";
  echo (strpos($value, 'calendario-academico') !== false ? 'has-calendario' : 'no-calendario') . "\n";
}
