<?php
require 'wp-load.php';
$page = get_post(509);
echo 'ID=' . $page->ID . PHP_EOL;
echo 'TITLE=' . $page->post_title . PHP_EOL;
echo 'STATUS=' . $page->post_status . PHP_EOL;
echo 'TEMPLATE=' . get_post_meta(509, '_wp_page_template', true) . PHP_EOL;
echo 'EDIT_MODE=' . get_post_meta(509, '_elementor_edit_mode', true) . PHP_EOL;
echo 'HAS_ELEMENTOR_DATA=' . (get_post_meta(509, '_elementor_data', true) ? 'yes' : 'no') . PHP_EOL;
