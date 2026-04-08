<?php
$mysqli = new mysqli('localhost', 'root', '', 'fisica');
if ($mysqli->connect_error) {
    fwrite(STDERR, "DB connection failed: {$mysqli->connect_error}\n");
    exit(1);
}
$mysqli->set_charset('utf8mb4');

function update_meta(mysqli $mysqli, int $postId, string $key, string $value): void {
    $stmt = $mysqli->prepare("SELECT meta_id FROM wp_postmeta WHERE post_id = ? AND meta_key = ? ORDER BY meta_id DESC LIMIT 1");
    $stmt->bind_param('is', $postId, $key);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($row) {
        $metaId = (int) $row['meta_id'];
        $stmt = $mysqli->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?");
        $stmt->bind_param('si', $value, $metaId);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $mysqli->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $postId, $key, $value);
        $stmt->execute();
        $stmt->close();
    }
}

function delete_meta(mysqli $mysqli, int $postId, string $key): void {
    $stmt = $mysqli->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key = ?");
    $stmt->bind_param('is', $postId, $key);
    $stmt->execute();
    $stmt->close();
}

function backup_elementor_data(mysqli $mysqli, int $postId, string $name): void {
    $stmt = $mysqli->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data' ORDER BY meta_id DESC LIMIT 1");
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_row();
    $stmt->close();
    $dir = 'c:/xampp/htdocs/fisica/wp-content/uploads/elementor-db-backups';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($dir . '/uniform-' . $name . '-' . date('Ymd-His') . '.json', $row[0] ?? '[]');
}

function update_post_content(mysqli $mysqli, int $postId, string $content): void {
    $stmt = $mysqli->prepare("UPDATE wp_posts SET post_content = ? WHERE ID = ?");
    $stmt->bind_param('si', $content, $postId);
    $stmt->execute();
    $stmt->close();
}

function fetch_post_content(mysqli $mysqli, int $postId): string {
    $stmt = $mysqli->prepare("SELECT post_content FROM wp_posts WHERE ID = ?");
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_row();
    $stmt->close();
    return $row[0] ?? '';
}

function fetch_elementor_json(mysqli $mysqli, int $postId): string {
    $stmt = $mysqli->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data' ORDER BY meta_id DESC LIMIT 1");
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_row();
    $stmt->close();
    return $row[0] ?? '[]';
}

function extract_links(string $html): array {
    preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $html, $matches, PREG_SET_ORDER);
    $items = [];
    foreach ($matches as $match) {
        $label = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5, 'UTF-8'))));
        if ($label === '') {
            continue;
        }
        $items[] = ['url' => $match[1], 'label' => $label];
    }
    return $items;
}

function extract_image_urls(array $elements): array {
    $urls = [];
    $walker = function(array $nodes) use (&$walker, &$urls): void {
        foreach ($nodes as $node) {
            if (($node['widgetType'] ?? '') === 'image') {
                $url = $node['settings']['image']['url'] ?? '';
                if ($url !== '') {
                    $urls[] = $url;
                }
            }
            if (!empty($node['elements']) && is_array($node['elements'])) {
                $walker($node['elements']);
            }
        }
    };
    $walker($elements);
    return array_values(array_unique($urls));
}

function render_resource_page(array $page, array $links): string {
    $cards = '';
    foreach ($links as $index => $item) {
        $num = str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT);
        $cards .= '<a class="fisica-resource-card" href="' . htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') . '">'
            . '<span class="fisica-resource-card__kicker">Item ' . $num . '</span>'
            . '<h3>' . htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') . '</h3>'
            . '<p>' . htmlspecialchars($page['cardText'], ENT_QUOTES, 'UTF-8') . '</p>'
            . '<span>Acessar documento</span>'
            . '</a>';
    }

    return '<section class="fisica-resource-page">'
        . '<div class="fisica-resource-page__hero"><div class="fisica-resource-page__hero-card">'
        . '<span class="fisica-resource-page__eyebrow">' . htmlspecialchars($page['eyebrow'], ENT_QUOTES, 'UTF-8') . '</span>'
        . '<h1>' . htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') . '</h1>'
        . '<p>' . htmlspecialchars($page['lead'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '</div></div>'
        . '<div class="fisica-resource-page__body">'
        . '<p class="fisica-resource-page__intro">' . htmlspecialchars($page['intro'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<div class="fisica-resource-grid">' . $cards . '</div>'
        . '</div></section>';
}

function render_table_page(array $page, string $contentHtml): string {
    return '<section class="fisica-resource-page">'
        . '<div class="fisica-resource-page__hero"><div class="fisica-resource-page__hero-card">'
        . '<span class="fisica-resource-page__eyebrow">' . htmlspecialchars($page['eyebrow'], ENT_QUOTES, 'UTF-8') . '</span>'
        . '<h1>' . htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') . '</h1>'
        . '<p>' . htmlspecialchars($page['lead'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '</div></div>'
        . '<div class="fisica-resource-page__body">'
        . '<p class="fisica-resource-page__intro">' . htmlspecialchars($page['intro'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<div class="fisica-table-surface">' . $contentHtml . '</div>'
        . '</div></section>';
}

function render_gallery_page(array $page, array $urls): string {
    $items = '';
    foreach ($urls as $url) {
        $safe = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $items .= '<a class="fisica-gallery-item" href="' . $safe . '"><img src="' . $safe . '" alt="' . htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') . '"></a>';
    }

    return '<section class="fisica-gallery-page">'
        . '<div class="fisica-gallery-page__hero"><div class="fisica-gallery-page__hero-card">'
        . '<span class="fisica-gallery-page__eyebrow">' . htmlspecialchars($page['eyebrow'], ENT_QUOTES, 'UTF-8') . '</span>'
        . '<h1>' . htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') . '</h1>'
        . '<p>' . htmlspecialchars($page['lead'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '</div></div>'
        . '<div class="fisica-gallery-page__body">'
        . '<p class="fisica-gallery-page__intro">' . htmlspecialchars($page['intro'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<div class="fisica-gallery-grid">' . $items . '</div>'
        . '</div></section>';
}

$resourcePages = [
    97 => [
        'slug' => 'projeto-monografia',
        'title' => 'Projeto de Monografia e Monografia',
        'eyebrow' => 'Documentos Acadêmicos',
        'lead' => 'Uma organização visual mais clara para acesso a temas, avisos, formulários e materiais relacionados ao percurso de monografia.',
        'intro' => 'Os documentos abaixo foram reorganizados em cards para facilitar localização, leitura e navegação no WordPress.',
        'cardText' => 'Documento institucional disponível para consulta, download e acompanhamento acadêmico.',
    ],
    120 => [
        'slug' => 'diplomacao',
        'title' => 'Diplomação',
        'eyebrow' => 'Procedimentos Acadêmicos',
        'lead' => 'Uma estrutura mais profissional para organizar formulários, orientações e documentos ligados ao processo de diplomação.',
        'intro' => 'O conteúdo desta página foi reorganizado para deixar o acesso aos materiais mais direto e mais confiável para os estudantes.',
        'cardText' => 'Acesse orientações e arquivos importantes para acompanhamento do processo de diplomação.',
    ],
    146 => [
        'slug' => 'solicitacoes-especiais',
        'title' => 'Solicitações Especiais',
        'eyebrow' => 'Procedimentos Acadêmicos',
        'lead' => 'Documentos e formulários reunidos em um formato mais claro para solicitações e requerimentos especiais.',
        'intro' => 'A página agora comunica melhor os caminhos formais para solicitações acadêmicas, com acesso mais simples aos arquivos.',
        'cardText' => 'Arquivo institucional disponível para consulta e encaminhamento acadêmico.',
    ],
    148 => [
        'slug' => 'aacc',
        'title' => 'AACC',
        'eyebrow' => 'Atividades Complementares',
        'lead' => 'Uma apresentação mais organizada para avisos, documentos e orientações sobre atividades acadêmico-científico-culturais.',
        'intro' => 'Os documentos foram reorganizados em um padrão visual mais consistente com a nova identidade do site.',
        'cardText' => 'Material oficial relacionado a avisos, regras e acompanhamento das AACC.',
    ],
    299 => [
        'slug' => 'horario-disciplinas',
        'title' => 'Horário das Disciplinas',
        'eyebrow' => 'Vida Acadêmica',
        'lead' => 'Os quadros de horário foram reorganizados em um formato mais legível, direto e profissional para consulta rápida.',
        'intro' => 'Selecione abaixo o período, grupo ou quadro correspondente para acessar o documento de horários.',
        'cardText' => 'Quadro acadêmico disponível para acesso direto em PDF.',
    ],
];

$tablePages = [
    295 => [
        'slug' => 'corpo-docente',
        'title' => 'Corpo Docente',
        'eyebrow' => 'Institucional',
        'lead' => 'Uma apresentação mais sólida para a consulta de docentes, áreas de atuação e organização acadêmica do instituto.',
        'intro' => 'O conteúdo existente foi mantido e reposicionado em uma superfície mais limpa e institucional, alinhada ao novo padrão visual do site.',
    ],
    301 => [
        'slug' => 'calendario-academico',
        'title' => 'Calendário Acadêmico',
        'eyebrow' => 'Vida Acadêmica',
        'lead' => 'As principais datas e prazos acadêmicos agora aparecem em uma página mais organizada, clara e profissional.',
        'intro' => 'A tabela original foi mantida, mas passou a ser exibida em um layout muito mais consistente com a nova identidade do site.',
    ],
];

$galleryPages = [
    650 => [
        'slug' => 'fotos-lef',
        'title' => 'Galeria do Laboratório de Ensino de Física',
        'eyebrow' => 'Galeria',
        'lead' => 'Uma galeria mais elegante para apresentar o espaço, os registros e a infraestrutura do laboratório.',
        'intro' => 'As imagens do laboratório agora aparecem em um grid mais profissional, com leitura visual mais forte e navegação mais agradável.',
    ],
    652 => [
        'slug' => 'fotos-lfpn',
        'title' => 'Galeria do Laboratório de Física Nuclear e Partículas',
        'eyebrow' => 'Galeria',
        'lead' => 'Um layout mais refinado para valorizar as imagens e a infraestrutura associadas ao laboratório.',
        'intro' => 'As fotos foram reorganizadas em um padrão visual unificado, coerente com a nova página principal do site.',
    ],
    654 => [
        'slug' => 'fotos-lfm',
        'title' => 'Galeria do Laboratório de Física Moderna',
        'eyebrow' => 'Galeria',
        'lead' => 'Uma apresentação mais contemporânea para registros visuais e ambientes de trabalho do laboratório.',
        'intro' => 'A galeria recebeu um grid mais limpo, com maior foco em imagem e melhor consistência institucional.',
    ],
    656 => [
        'slug' => 'fotos-hepgrid',
        'title' => 'Galeria do Laboratório HEPGrid',
        'eyebrow' => 'Galeria',
        'lead' => 'Uma vitrine visual mais forte para comunicar o ambiente, os equipamentos e a identidade do laboratório.',
        'intro' => 'O conteúdo fotográfico foi reorganizado para ficar mais bonito, mais legível e mais alinhado à reformulação geral do site.',
    ],
    658 => [
        'slug' => 'fotos-lieta',
        'title' => 'Galeria do Laboratório LIETA',
        'eyebrow' => 'Galeria',
        'lead' => 'Uma galeria mais organizada para apresentar o laboratório com acabamento visual mais profissional.',
        'intro' => 'As imagens foram redistribuídas em um grid consistente com o novo padrão gráfico aplicado ao restante do projeto.',
    ],
    662 => [
        'slug' => 'fotos-lfmedicas',
        'title' => 'Galeria do Laboratório de Física Médica',
        'eyebrow' => 'Galeria',
        'lead' => 'Uma nova apresentação para valorizar a estrutura e os registros visuais do laboratório de física médica.',
        'intro' => 'A página foi reformulada para dar mais qualidade visual às imagens e melhor continuidade à navegação do site.',
    ],
];

$updated = [];

foreach ($resourcePages as $postId => $page) {
    $content = fetch_post_content($mysqli, $postId);
    $links = extract_links($content);
    if (!$links) {
        continue;
    }
    backup_elementor_data($mysqli, $postId, $page['slug']);
    $html = render_resource_page($page, $links);
    $data = [[
        'id' => 'uniform_' . $postId,
        'elType' => 'container',
        'settings' => ['content_width' => 'full', 'flex_direction' => 'column'],
        'elements' => [[
            'id' => 'uniform_html_' . $postId,
            'elType' => 'widget',
            'widgetType' => 'html',
            'settings' => ['html' => $html],
            'elements' => [],
        ]],
        'isInner' => false,
    ]];
    update_meta($mysqli, $postId, '_elementor_data', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    update_meta($mysqli, $postId, '_elementor_version', '3.32.1');
    update_meta($mysqli, $postId, '_elementor_edit_mode', 'builder');
    delete_meta($mysqli, $postId, '_elementor_css');
    delete_meta($mysqli, $postId, '_elementor_page_assets');
    delete_meta($mysqli, $postId, '_elementor_element_cache');
    update_post_content($mysqli, $postId, $html);
    $updated[] = $postId;
}

foreach ($tablePages as $postId => $page) {
    $content = fetch_post_content($mysqli, $postId);
    if (trim($content) === '') {
        continue;
    }
    backup_elementor_data($mysqli, $postId, $page['slug']);
    $html = render_table_page($page, $content);
    $data = [[
        'id' => 'uniform_' . $postId,
        'elType' => 'container',
        'settings' => ['content_width' => 'full', 'flex_direction' => 'column'],
        'elements' => [[
            'id' => 'uniform_html_' . $postId,
            'elType' => 'widget',
            'widgetType' => 'html',
            'settings' => ['html' => $html],
            'elements' => [],
        ]],
        'isInner' => false,
    ]];
    update_meta($mysqli, $postId, '_elementor_data', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    update_meta($mysqli, $postId, '_elementor_version', '3.32.1');
    update_meta($mysqli, $postId, '_elementor_edit_mode', 'builder');
    delete_meta($mysqli, $postId, '_elementor_css');
    delete_meta($mysqli, $postId, '_elementor_page_assets');
    delete_meta($mysqli, $postId, '_elementor_element_cache');
    update_post_content($mysqli, $postId, $html);
    $updated[] = $postId;
}

foreach ($galleryPages as $postId => $page) {
    $json = fetch_elementor_json($mysqli, $postId);
    $elements = json_decode($json, true) ?: [];
    $urls = extract_image_urls($elements);
    if (!$urls) {
        $content = fetch_post_content($mysqli, $postId);
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $matches);
        $urls = array_values(array_unique($matches[1] ?? []));
    }
    if (!$urls) {
        continue;
    }
    backup_elementor_data($mysqli, $postId, $page['slug']);
    $html = render_gallery_page($page, $urls);
    $data = [[
        'id' => 'uniform_' . $postId,
        'elType' => 'container',
        'settings' => ['content_width' => 'full', 'flex_direction' => 'column'],
        'elements' => [[
            'id' => 'uniform_html_' . $postId,
            'elType' => 'widget',
            'widgetType' => 'html',
            'settings' => ['html' => $html],
            'elements' => [],
        ]],
        'isInner' => false,
    ]];
    update_meta($mysqli, $postId, '_elementor_data', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    update_meta($mysqli, $postId, '_elementor_version', '3.32.1');
    update_meta($mysqli, $postId, '_elementor_edit_mode', 'builder');
    delete_meta($mysqli, $postId, '_elementor_css');
    delete_meta($mysqli, $postId, '_elementor_page_assets');
    delete_meta($mysqli, $postId, '_elementor_element_cache');
    update_post_content($mysqli, $postId, $html);
    $updated[] = $postId;
}

foreach ($updated as $postId) {
    echo "Updated {$postId}\n";
}
?>
