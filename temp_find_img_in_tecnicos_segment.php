<?php
$html = file_get_contents('http://localhost/fisica/index.php/tecnicos/?v=20260506h');
$start = strpos($html, '<div data-elementor-type="wp-page" data-elementor-id="509"');
$end = strpos($html, '</footer>', $start);
$segment = ($start !== false && $end !== false) ? substr($html, $start, $end - $start) : $html;
$imgPos = strpos($segment, '<img');
if ($imgPos !== false) {
  echo substr($segment, max(0, $imgPos - 200), 500);
} else {
  echo 'NO_IMG';
}
