<?php
$mysqli = new mysqli('localhost', 'root', '', 'fisica');
$map = [9 => 'home', 25 => 'header', 60 => 'footer'];
foreach ($map as $id => $name) {
  $res = $mysqli->query("SELECT meta_value FROM wp_postmeta WHERE post_id={$id} AND meta_key='_elementor_data' ORDER BY meta_id DESC LIMIT 1");
  $row = $res ? $res->fetch_row() : null;
  $meta = $row[0] ?? '[]';
  file_put_contents("c:/xampp/htdocs/fisica/{$name}-elementor-data.json", json_encode(json_decode($meta, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}
