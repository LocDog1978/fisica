<?php
require __DIR__ . '/wp-load.php';
$data = (string) get_post_meta(9, '_elementor_data', true);
@('recepcao-dos-estudantes-2026-1','visita-tecnica-inpe-2026','calendario-academico') | foreach ($needle) {
  echo $needle . ': ' . (strpos($data, $needle) !== false ? 'true' : 'false') . PHP_EOL;
}
