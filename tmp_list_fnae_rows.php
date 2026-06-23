<?php
require 'wp-load.php';
$p = get_page_by_path('corpo-docente');
$content = $p ? $p->post_content : '';
preg_match('/<h2>Física Nuclear e Altas Energias<\/h2>(.*?)<\/section>/su', $content, $m);
if (!empty($m[1])) {
    preg_match_all('/<tr class="fisica-docentes-row".*?<td data-label="Docente">(.*?)<\/td>.*?<td data-label="&Aacute;rea de pesquisa">(.*?)<\/td>.*?<td data-label="Sala">(.*?)<\/td>.*?<td data-label="Lattes">(.*?)<\/td><\/tr>/su', $m[1], $rows, PREG_SET_ORDER);
    foreach ($rows as $row) {
        echo html_entity_decode(strip_tags($row[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8') . ' | ';
        echo html_entity_decode(strip_tags($row[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8') . ' | ';
        echo html_entity_decode(strip_tags($row[3]), ENT_QUOTES | ENT_HTML5, 'UTF-8') . ' | ';
        echo html_entity_decode(strip_tags($row[4]), ENT_QUOTES | ENT_HTML5, 'UTF-8') . PHP_EOL;
    }
}
