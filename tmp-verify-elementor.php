<?php
$mysqli = new mysqli('localhost', 'root', '', 'fisica');
$mysqli->set_charset('utf8mb4');
foreach ([9,25,60] as $id) {
  $stmt = $mysqli->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data' ORDER BY meta_id DESC LIMIT 1");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $json = $stmt->get_result()->fetch_row()[0] ?? '[]';
  $data = json_decode($json, true);
  echo "=== {$id} ===\n";
  foreach ($data as $el) {
    $type = $el['elType'] ?? '';
    $idEl = $el['id'] ?? '';
    echo "- {$type} {$idEl}\n";
    foreach (($el['elements'] ?? []) as $child) {
      $widget = $child['widgetType'] ?? ($child['elType'] ?? '');
      $childId = $child['id'] ?? '';
      echo "  - {$widget} {$childId}\n";
    }
  }
}
?>
