<?php
$html = file_get_contents('http://localhost/fisica/index.php/corpo-docente/?v=20260506e');
echo 'HAS_DOCENTES_TITLE=' . (strpos($html, '<h1>Corpo Docente</h1>') !== false ? 'yes' : 'no') . PHP_EOL;
echo 'HAS_TECNICOS_TITLE=' . (strpos($html, '<h1>Técnicos</h1>') !== false ? 'yes' : 'no') . PHP_EOL;
