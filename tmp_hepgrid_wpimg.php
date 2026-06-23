<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
foreach ([1250,1251] as $id) {
  echo 'ID=' . $id . PHP_EOL;
  echo 'FULL=' . wp_get_attachment_image_url($id, 'full') . PHP_EOL;
  echo 'LARGE=' . wp_get_attachment_image_url($id, 'large') . PHP_EOL;
  echo 'MEDIUM=' . wp_get_attachment_image_url($id, 'medium') . PHP_EOL;
  echo 'IMG=' . wp_get_attachment_image($id, 'large', false, [
    'loading' => 'lazy',
    'decoding' => 'async',
    'alt' => 'Laboratório HEPGrid',
    'sizes' => '(max-width: 767px) 100vw, (max-width: 1100px) 50vw, 33vw'
  ]) . PHP_EOL;
}
