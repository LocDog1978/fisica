<?php
require __DIR__ . '/wp-load.php';
$post = get_post(654);
setup_postdata($post);
$GLOBALS['post'] = $post;
$query = new WP_Query(['page_id' => 654, 'post_type' => 'page']);
$GLOBALS['wp_query'] = $query;
$GLOBALS['wp_the_query'] = $query;
if ($query->have_posts()) { $query->the_post(); }
$content = apply_filters('the_content', $post->post_content);
echo 'POST_COUNT=' . substr_count($post->post_content, '<a ') . PHP_EOL;
echo 'FILTER_HAS_NEW=' . (strpos($content, 'GEO_3920-2.jpg') !== false ? '1' : '0') . PHP_EOL;
echo 'FILTER_ANCHORS=' . substr_count($content, '<a href="http://localhost/fisica/wp-content/uploads/') . PHP_EOL;
echo substr($content, -500);
