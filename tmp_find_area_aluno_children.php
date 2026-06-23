<?php
require __DIR__ . '/wp-load.php';
$slugs = ['iniciacao-cientifica','monitorias','estagios','projeto-de-monografia-e-monografia'];
foreach ($slugs as $slug) {
  $page = get_page_by_path($slug, OBJECT, 'page');
  if ($page) {
    echo $page->ID . '|' . $page->post_name . '|' . $page->post_title . PHP_EOL;
  }
}
