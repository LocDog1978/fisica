<?php
require __DIR__ . '/wp-load.php';
$post = get_post(1094);
setup_postdata($post);
$GLOBALS['post'] = $post;
$query = new WP_Query(['page_id' => 1094, 'post_type' => 'page']);
$GLOBALS['wp_query'] = $query;
$GLOBALS['wp_the_query'] = $query;
if ($query->have_posts()) { $query->the_post(); }
$content = apply_filters('the_content', $post->post_content);
echo $content;
