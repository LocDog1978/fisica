require 'wp-load.php';
$ids = get_posts([
  'post_type' => 'attachment',
  'post_mime_type' => 'image',
  'post_status' => 'inherit',
  'posts_per_page' => 5,
  'orderby' => 'date',
  'order' => 'DESC',
  'fields' => 'ids',
]);
$page = get_page_by_path('visita-tecnica-inpe-2026', OBJECT, 'page');
$data = [
  'latest_ids' => array_map('intval', $ids),
  'page_id' => $page ? (int) $page->ID : 0,
  'page_excerpt' => $page ? get_post_field('post_excerpt', $page->ID) : '',
];
echo wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
