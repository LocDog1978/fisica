<?php
require 'wp-load.php';
$html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display(509);
echo substr($html, 0, 2200);
