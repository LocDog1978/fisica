<?php
require 'wp-load.php';
$pages = get_posts(['post_type'=>'page','post_status'=>['publish','draft','private'],'posts_per_page'=>-1,'orderby'=>'ID','order'=>'ASC']);
foreach ($pages as $p) {
  if ($p->ID >= 250) {
    echo $p->ID . '|' . $p->post_title . '|' . $p->post_name . '|' . $p->post_status . PHP_EOL;
  }
}
