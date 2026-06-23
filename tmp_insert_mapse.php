<?php
require 'wp-load.php';

$page = get_page_by_path('corpo-docente');
if (!$page) {
    fwrite(STDERR, "Page not found\n");
    exit(1);
}

$content = $page->post_content;
if (strpos($content, 'Mapse Barroso Ferreira Filho') !== false) {
    echo "ALREADY_PRESENT\n";
    exit(0);
}

$needle = '<tr class="fisica-docentes-row" data-search="marcia begalli f&iacute;sica experimental de altas energias 3002-a"><td data-label="Docente">Marcia Begalli</td><td data-label="&Aacute;rea de pesquisa">F&iacute;sica Experimental de Altas Energias</td><td data-label="Sala">3002-A</td><td data-label="Lattes"><a href="http://lattes.cnpq.br/5447016634798000" target="_blank" rel="noopener noreferrer" aria-label="Abrir curr&iacute;culo Lattes de Marcia Begalli">Acessar</a></td></tr>';

$insert = $needle . '<tr class="fisica-docentes-row" data-search="mapse barroso ferreira filho f&iacute;sica experimental de altas energias 3008-a"><td data-label="Docente">Mapse Barroso Ferreira Filho</td><td data-label="&Aacute;rea de pesquisa">F&iacute;sica Experimental de Altas Energias</td><td data-label="Sala">3008-A</td><td data-label="Lattes"><a href="http://lattes.cnpq.br/" target="_blank" rel="noopener noreferrer" aria-label="Abrir curr&iacute;culo Lattes de Mapse Barroso Ferreira Filho">Acessar</a></td></tr>';

$new_content = str_replace($needle, $insert, $content, $count);
if ($count !== 1) {
    fwrite(STDERR, "Needle replacement count: $count\n");
    exit(1);
}

$result = wp_update_post([
    'ID' => $page->ID,
    'post_content' => $new_content,
], true);

if (is_wp_error($result)) {
    fwrite(STDERR, $result->get_error_message() . "\n");
    exit(1);
}

echo "UPDATED\n";
