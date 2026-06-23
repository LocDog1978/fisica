<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
$post = get_post(656);
echo 'TITLE=' . $post->post_title . PHP_EOL;
echo 'CONTENT_LEN=' . strlen((string)$post->post_content) . PHP_EOL;
$content = (string)$post->post_content;
$pos = strpos($content, 'fisica-gallery-grid');
if ($pos !== false) {
  echo substr($content, max(0, $pos - 500), 3500);
}
