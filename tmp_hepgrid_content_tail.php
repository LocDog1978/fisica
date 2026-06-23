<?php
require 'C:/xampp/htdocs/fisica/wp-load.php';
$content = (string)get_post(656)->post_content;
$pos = strpos($content, 'fisica-gallery-grid');
if ($pos !== false) {
  echo substr($content, $pos, 2600);
}
