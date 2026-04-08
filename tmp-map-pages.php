<?php
$mysqli = new mysqli('localhost', 'root', '', 'fisica');
$mysqli->set_charset('utf8mb4');
$ids = [97,120,146,148,295,299,301,555,564,573,591,601,610,616,621,626,634,641,650,652,654,656,658,662,989,991,993];
foreach ($ids as $id) {
  $titleRes = $mysqli->query("SELECT post_title FROM wp_posts WHERE ID={$id}");
  $title = $titleRes ? ($titleRes->fetch_row()[0] ?? '') : '';
  $metaRes = $mysqli->query("SELECT meta_value FROM wp_postmeta WHERE post_id={$id} AND meta_key='_elementor_data' ORDER BY meta_id DESC LIMIT 1");
  $json = $metaRes ? ($metaRes->fetch_row()[0] ?? '[]') : '[]';
  $data = json_decode($json, true);
  echo "=== {$id} | {$title} ===\n";
  foreach ((array)$data as $el) {
    $type = $el['elType'] ?? 'unknown';
    $widget = $el['widgetType'] ?? '';
    $settings = $el['settings'] ?? [];
    $label = $widget ?: $type;
    if (isset($settings['title'])) { $label .= ' title=' . $settings['title']; }
    if (isset($settings['shortcode'])) { $label .= ' shortcode=' . $settings['shortcode']; }
    if (isset($settings['editor'])) {
      $text = trim(preg_replace('/\s+/', ' ', strip_tags($settings['editor'])));
      if ($text !== '') { $label .= ' text=' . substr($text, 0, 90); }
    }
    echo "- {$label}\n";
    foreach (($el['elements'] ?? []) as $child) {
      $ctype = $child['widgetType'] ?? ($child['elType'] ?? 'unknown');
      $csettings = $child['settings'] ?? [];
      $clabel = $ctype;
      if (isset($csettings['title'])) { $clabel .= ' title=' . $csettings['title']; }
      if (isset($csettings['shortcode'])) { $clabel .= ' shortcode=' . $csettings['shortcode']; }
      if (isset($csettings['editor'])) {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($csettings['editor'])));
        if ($text !== '') { $clabel .= ' text=' . substr($text, 0, 90); }
      }
      echo "  - {$clabel}\n";
    }
  }
  echo "\n";
}
?>
