<?php
require __DIR__ . '/wp-load.php';
foreach ([16,1188] as $id) {
  $post = get_post($id);
  echo "ID={$id}|slug={$post->post_name}|title={$post->post_title}\n";
  echo 'POST_LEN=' . strlen($post->post_content) . PHP_EOL;
  echo $post->post_content . PHP_EOL . "\n---POST-END---\n";
  $data = get_post_meta($id, '_elementor_data', true);
  echo 'ELEMENTOR_LEN=' . (is_string($data) ? strlen($data) : 0) . PHP_EOL;
  if (is_string($data)) {
    echo substr($data, 0, 16000) . PHP_EOL;
  }
  echo "\n===PAGE-END===\n";
}
