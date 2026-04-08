<?php
$mysqli = new mysqli('localhost', 'root', '', 'fisica');
$mysqli->set_charset('utf8mb4');
$ids = [555,564,573,591,601,610,616,621,626,634,641,989,991,993];
foreach ($ids as $id) {
  $title = $mysqli->query("SELECT post_title FROM wp_posts WHERE ID={$id}")->fetch_row()[0] ?? '';
  $json = $mysqli->query("SELECT meta_value FROM wp_postmeta WHERE post_id={$id} AND meta_key='_elementor_data' ORDER BY meta_id DESC LIMIT 1")->fetch_row()[0] ?? '[]';
  $data = json_decode($json, true);
  echo "=== {$id} | {$title} ===\n";
  echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  echo "\n\n";
}
?>
