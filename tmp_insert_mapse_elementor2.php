<?php
require 'wp-load.php';

$page = get_page_by_path('corpo-docente');
if (!$page) exit(1);
$meta = get_post_meta($page->ID, '_elementor_data', true);

$needle = '<tr class=\"fisica-docentes-row\" data-search=\"marcia begalli f&iacute;sica experimental de altas energias 3002-a\"><td data-label=\"Docente\">Marcia Begalli</td><td data-label=\"&Aacute;rea de pesquisa\">F&iacute;sica Experimental de Altas Energias</td><td data-label=\"Sala\">3002-A</td><td data-label=\"Lattes\"><a href=\"http://lattes.cnpq.br/5447016634798000\" target=\"_blank\" rel=\"noopener noreferrer\" aria-label=\"Abrir curr&iacute;culo Lattes de Marcia Begalli\">Acessar</a></td></tr>';
$insert = $needle . '<tr class=\"fisica-docentes-row\" data-search=\"mapse barroso ferreira filho f&iacute;sica experimental de altas energias 3008-a\"><td data-label=\"Docente\">Mapse Barroso Ferreira Filho</td><td data-label=\"&Aacute;rea de pesquisa\">F&iacute;sica Experimental de Altas Energias</td><td data-label=\"Sala\">3008-A</td><td data-label=\"Lattes\"><a href=\"http://lattes.cnpq.br/\" target=\"_blank\" rel=\"noopener noreferrer\" aria-label=\"Abrir curr&iacute;culo Lattes de Mapse Barroso Ferreira Filho\">Acessar</a></td></tr>';

$new_meta = str_replace($needle, $insert, $meta, $count);
if ($count !== 1) {
    fwrite(STDERR, "Elementor replacement count: $count\n");
    exit(1);
}

update_post_meta($page->ID, '_elementor_data', wp_slash($new_meta));
delete_post_meta($page->ID, '_elementor_element_cache');
echo "ELEMENTOR_UPDATED\n";
