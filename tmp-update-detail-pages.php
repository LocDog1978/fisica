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
    file_put_contents($dir . '/detail-' . $name . '-' . date('Ymd-His') . '.json', $row[0] ?? '[]');
}

function update_post_content(mysqli $mysqli, int $postId, string $content): void {
    $stmt = $mysqli->prepare("UPDATE wp_posts SET post_content = ? WHERE ID = ?");
    $stmt->bind_param('si', $content, $postId);
    $stmt->execute();
    $stmt->close();
}

function render_detail_html(array $page): string {
    $highlights = '';
    foreach ($page['highlights'] as $item) {
        $highlights .= '<div class="fisica-detail-page__highlight"><strong>' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '</strong><span>' . htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') . '</span></div>';
    }

    return '<section class="fisica-detail-page">'
        . '<div class="fisica-detail-page__hero">'
        . '<div class="fisica-detail-page__hero-card">'
        . '<span class="fisica-detail-page__eyebrow">' . htmlspecialchars($page['eyebrow'], ENT_QUOTES, 'UTF-8') . '</span>'
        . '<h1 class="fisica-detail-page__title">' . htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') . '</h1>'
        . '<p class="fisica-detail-page__lead">' . htmlspecialchars($page['lead'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '</div></div>'
        . '<div class="fisica-detail-page__wrap">'
        . '<div class="fisica-detail-page__content">'
        . '<div class="fisica-detail-page__panel">'
        . '<section class="fisica-detail-page__section"><h2>Visão geral</h2><p>' . htmlspecialchars($page['overview'], ENT_QUOTES, 'UTF-8') . '</p></section>'
        . '<section class="fisica-detail-page__section"><h2>Destaques</h2><div class="fisica-detail-page__highlights">' . $highlights . '</div></section>'
        . '<section class="fisica-detail-page__section"><h2>Apresentação</h2><p>' . htmlspecialchars($page['details'], ENT_QUOTES, 'UTF-8') . '</p></section>'
        . '</div></div>'
        . '<aside class="fisica-detail-page__sidebar">'
        . '<div class="fisica-detail-page__sidebar-card"><h3>Navegação relacionada</h3><p>' . htmlspecialchars($page['sidebar'], ENT_QUOTES, 'UTF-8') . '</p><a class="fisica-detail-page__sidebar-link" href="' . htmlspecialchars($page['sidebarLink'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($page['sidebarCta'], ENT_QUOTES, 'UTF-8') . '</a></div>'
        . '<div class="fisica-detail-page__sidebar-card"><h3>Observação</h3><p>Esta página recebeu uma estrutura visual mais sólida e já está pronta para receber conteúdo institucional definitivo com muito mais qualidade editorial.</p></div>'
        . '</aside>'
        . '</div></section>';
}

$pages = [
    555 => [
        'slug' => 'ciencias-biomedicas-ambientais',
        'title' => 'Ciências Biomédicas e Ambientais',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma apresentação mais clara para estudos interdisciplinares ligados à saúde, ao meio ambiente e às aplicações científicas da física.',
        'overview' => 'Esta página passa a introduzir a linha de pesquisa de maneira mais organizada, valorizando a relação entre fundamentos da física, aplicações biomédicas e investigação ambiental.',
        'details' => 'O novo layout cria uma base institucional para receber professores, projetos, laboratórios envolvidos, publicações e oportunidades acadêmicas de forma muito mais profissional do que o conteúdo provisório anterior.',
        'sidebar' => 'Use esta seção para aprofundar a navegação entre áreas de pesquisa, laboratórios e páginas institucionais relacionadas.',
        'sidebarLink' => 'http://localhost/fisica/#programas',
        'sidebarCta' => 'Voltar para áreas e programas',
        'highlights' => [
            ['title' => 'Interdisciplinaridade', 'text' => 'Conexão entre física, saúde, meio ambiente e inovação.'],
            ['title' => 'Pesquisa aplicada', 'text' => 'Potencial para projetos com impacto acadêmico e social.'],
            ['title' => 'Expansão de conteúdo', 'text' => 'Página preparada para receber dados, equipes e publicações.'],
        ],
    ],
    564 => [
        'slug' => 'aplicacoes-industriais',
        'title' => 'Aplicações Industriais de Radioisótopos',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma estrutura mais profissional para apresentar aplicações científicas e tecnológicas voltadas a processos industriais.',
        'overview' => 'A página agora introduz o tema de forma institucional, com espaço adequado para destacar pesquisa aplicada, colaboração técnica e possíveis interfaces com o setor produtivo.',
        'details' => 'Antes havia apenas um título genérico do Elementor. Agora a página oferece um ponto de partida coerente para incorporar textos, resultados, professores, infraestrutura e exemplos de aplicação.',
        'sidebar' => 'A nova navegação lateral ajuda o visitante a retornar rapidamente para outras linhas e programas do instituto.',
        'sidebarLink' => 'http://localhost/fisica/#programas',
        'sidebarCta' => 'Explorar outras áreas',
        'highlights' => [
            ['title' => 'Tecnologia aplicada', 'text' => 'Enfoque em soluções e usos práticos da física no contexto industrial.'],
            ['title' => 'Integração técnica', 'text' => 'Espaço para apresentar processos, métodos e parcerias.'],
            ['title' => 'Comunicação institucional', 'text' => 'Layout adequado para conteúdo acadêmico e público externo.'],
        ],
    ],
    573 => [
        'slug' => 'ensino-de-fisico',
        'title' => 'Ensino de Física na Formação de Professores',
        'eyebrow' => 'Formação e Pesquisa',
        'lead' => 'Uma página organizada para valorizar ensino, didática, formação docente e práticas educacionais ligadas à física.',
        'overview' => 'A nova composição visual favorece a leitura e prepara a página para apresentar projetos, disciplinas, iniciativas de formação e ações voltadas ao ensino de física.',
        'details' => 'O conteúdo agora pode crescer com muito mais clareza, incluindo grupos responsáveis, atividades, experiências didáticas, produção acadêmica e oportunidades para estudantes.',
        'sidebar' => 'Esta seção pode conectar visitantes a iniciativas acadêmicas relacionadas, páginas de alunos e conteúdos institucionais.',
        'sidebarLink' => 'http://localhost/fisica/index.php/area-do-aluno/',
        'sidebarCta' => 'Ir para a área do aluno',
        'highlights' => [
            ['title' => 'Formação docente', 'text' => 'Espaço para iniciativas ligadas à formação inicial e continuada.'],
            ['title' => 'Prática educacional', 'text' => 'Apresentação mais adequada de experiências e metodologias de ensino.'],
            ['title' => 'Base editorial', 'text' => 'Estrutura pronta para textos, projetos e referências.'],
        ],
    ],
    591 => [
        'slug' => 'magnetismo',
        'title' => 'Magnetismo e Materiais Magnéticos',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma identidade visual mais forte para apresentar estudos sobre propriedades magnéticas, materiais e aplicações associadas.',
        'overview' => 'A nova organização destaca o tema e prepara a página para receber pesquisadores, linhas de trabalho, equipamentos, produção científica e projetos relacionados.',
        'details' => 'Além de corrigir o estado provisório da página, a nova composição estabelece uma base coerente para crescimento de conteúdo técnico com boa leitura no WordPress.',
        'sidebar' => 'Esta navegação pode conectar o visitante a outras áreas de física aplicada e a páginas laboratoriais do instituto.',
        'sidebarLink' => 'http://localhost/fisica/#laboratorios',
        'sidebarCta' => 'Ver laboratórios',
        'highlights' => [
            ['title' => 'Materiais', 'text' => 'Espaço adequado para descrever classes de materiais e suas propriedades.'],
            ['title' => 'Pesquisa experimental', 'text' => 'Base para apresentar equipamentos, testes e metodologias.'],
            ['title' => 'Aplicações', 'text' => 'Estrutura pronta para usos acadêmicos e tecnológicos.'],
        ],
    ],
    601 => [
        'slug' => 'nuclear-aplicada',
        'title' => 'Física Nuclear Aplicada',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma página reformulada para comunicar com mais clareza a dimensão aplicada da física nuclear no instituto.',
        'overview' => 'O novo layout fortalece a apresentação da área e cria espaço para descrever projetos, tecnologias, metodologias e articulações com ensino e pesquisa.',
        'details' => 'Com essa base, a página pode evoluir para reunir pesquisadores, resultados, infraestrutura, publicações e conteúdos voltados a diferentes públicos.',
        'sidebar' => 'A seção lateral apoia a navegação para laboratórios, programas e páginas estratégicas do site.',
        'sidebarLink' => 'http://localhost/fisica/#programas',
        'sidebarCta' => 'Voltar para programas',
        'highlights' => [
            ['title' => 'Aplicação científica', 'text' => 'Comunicação mais precisa para pesquisa com ênfase aplicada.'],
            ['title' => 'Infraestrutura', 'text' => 'Preparada para apresentar laboratórios, métodos e recursos.'],
            ['title' => 'Expansão', 'text' => 'Layout pronto para conteúdo institucional definitivo.'],
        ],
    ],
    610 => [
        'slug' => 'materia-condensada',
        'title' => 'Física da Matéria Condensada',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma estrutura contemporânea para comunicar estudos em materiais, sistemas físicos complexos e propriedades da matéria.',
        'overview' => 'A nova organização visual permite apresentar a área com mais autoridade, criando uma hierarquia clara para textos, projetos e equipes responsáveis.',
        'details' => 'O resultado é uma página muito mais útil para futura publicação de conteúdo técnico e institucional, substituindo o conteúdo genérico que existia antes.',
        'sidebar' => 'Use a navegação lateral para conectar a página às demais áreas e à infraestrutura experimental do instituto.',
        'sidebarLink' => 'http://localhost/fisica/#laboratorios',
        'sidebarCta' => 'Conhecer os laboratórios',
        'highlights' => [
            ['title' => 'Sistemas materiais', 'text' => 'Página pronta para destacar temas, objetos e métodos de estudo.'],
            ['title' => 'Leitura qualificada', 'text' => 'Hierarquia visual mais adequada para conteúdo acadêmico.'],
            ['title' => 'Base institucional', 'text' => 'Layout compatível com expansão editorial futura.'],
        ],
    ],
    616 => [
        'slug' => 'sensores-e-fibras-oticas',
        'title' => 'Sensores e Fibras Óticas',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma página redesenhada para valorizar aplicações em sensores, medição e tecnologias ópticas.',
        'overview' => 'A estrutura agora favorece uma apresentação mais clara da área, com espaço para explicar suas possibilidades acadêmicas, experimentais e tecnológicas.',
        'details' => 'Com esse novo layout, fica muito mais fácil adicionar descrições detalhadas, linhas de trabalho, equipamentos, equipe e oportunidades relacionadas.',
        'sidebar' => 'A navegação lateral cria um caminho simples para o visitante voltar às áreas de pesquisa e páginas institucionais do curso.',
        'sidebarLink' => 'http://localhost/fisica/#programas',
        'sidebarCta' => 'Ver outras linhas',
        'highlights' => [
            ['title' => 'Tecnologias ópticas', 'text' => 'Espaço para apresentar fundamentos, usos e aplicações.'],
            ['title' => 'Pesquisa aplicada', 'text' => 'Estrutura pronta para projetos, protótipos e medições.'],
            ['title' => 'Organização', 'text' => 'Página reformulada para leitura mais profissional.'],
        ],
    ],
    621 => [
        'slug' => 'teoria-quantica',
        'title' => 'Teoria Quântica de Campos',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma apresentação mais elegante e sólida para conteúdos de física teórica e fundamentos avançados.',
        'overview' => 'A nova página oferece uma base muito melhor para introduzir a área, seus interesses de pesquisa, seus vínculos acadêmicos e seus desdobramentos conceituais.',
        'details' => 'O objetivo aqui é substituir a aparência provisória por uma estrutura que consiga sustentar textos mais densos sem perder clareza visual.',
        'sidebar' => 'A navegação lateral ajuda a conectar esta área a outras páginas de teoria, graduação e pesquisa institucional.',
        'sidebarLink' => 'http://localhost/fisica/index.php/dft-departamento-de-fisica-teorica/',
        'sidebarCta' => 'Ir para o departamento',
        'highlights' => [
            ['title' => 'Fundamentos', 'text' => 'Estrutura adequada para temas conceituais e formulações avançadas.'],
            ['title' => 'Página editorial', 'text' => 'Melhor suporte visual para textos densos e acadêmicos.'],
            ['title' => 'Continuidade', 'text' => 'Base pronta para futuras publicações institucionais.'],
        ],
    ],
    626 => [
        'slug' => 'gravitacao',
        'title' => 'Gravitação e Cosmologia',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma nova apresentação para comunicar estudos ligados à estrutura do universo, gravitação e cosmologia.',
        'overview' => 'A página passa a ter uma abertura mais forte e adequada à importância do tema, com melhor hierarquia para conteúdos de pesquisa e divulgação institucional.',
        'details' => 'Com isso, a área ganha uma base mais preparada para reunir textos introdutórios, pesquisadores, projetos, eventos e produção acadêmica.',
        'sidebar' => 'A navegação lateral facilita o retorno às páginas principais e a conexão com outros conteúdos de pesquisa.',
        'sidebarLink' => 'http://localhost/fisica/#programas',
        'sidebarCta' => 'Voltar para pesquisa',
        'highlights' => [
            ['title' => 'Cosmologia', 'text' => 'Espaço para contextualizar temas e frentes de investigação.'],
            ['title' => 'Comunicação clara', 'text' => 'Layout pensado para melhorar compreensão e leitura.'],
            ['title' => 'Projeção institucional', 'text' => 'Visual mais compatível com a relevância acadêmica da área.'],
        ],
    ],
    634 => [
        'slug' => 'matematica',
        'title' => 'Física Matemática',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma página redesenhada para sustentar conteúdos de alta densidade conceitual com mais clareza e elegância.',
        'overview' => 'A nova composição visual ajuda a apresentar a área com sobriedade, preparando a página para textos institucionais, fundamentos, linhas de trabalho e referências.',
        'details' => 'Essa base substitui a aparência genérica anterior e cria um ambiente muito mais apropriado para expansão futura do conteúdo acadêmico.',
        'sidebar' => 'A navegação lateral pode ser usada para conectar esta página a departamentos, áreas correlatas e páginas de formação.',
        'sidebarLink' => 'http://localhost/fisica/index.php/dft-departamento-de-fisica-teorica/',
        'sidebarCta' => 'Explorar o departamento',
        'highlights' => [
            ['title' => 'Base conceitual', 'text' => 'Suporte visual adequado para temas formais e analíticos.'],
            ['title' => 'Leitura fluida', 'text' => 'Melhor hierarquia para textos mais longos ou densos.'],
            ['title' => 'Estrutura futura', 'text' => 'Página pronta para detalhamento institucional.'],
        ],
    ],
    641 => [
        'slug' => 'altas-energias',
        'title' => 'Física Experimental de Altas Energias',
        'eyebrow' => 'Linha de Pesquisa',
        'lead' => 'Uma apresentação mais robusta para uma área ligada a grandes colaborações, detectores e investigação experimental.',
        'overview' => 'A página agora oferece uma organização mais profissional, adequada para explicar a área, seus contextos de trabalho e suas relações com infraestrutura e pesquisa.',
        'details' => 'Essa reformulação substitui o estado anterior por uma base sólida para descrição de projetos, participação em redes de pesquisa e oportunidades acadêmicas.',
        'sidebar' => 'A navegação relacionada aproxima esta página das demais áreas e dos laboratórios em destaque no site.',
        'sidebarLink' => 'http://localhost/fisica/index.php/fotos-hepgrid/',
        'sidebarCta' => 'Ver fotos do HEPGrid',
        'highlights' => [
            ['title' => 'Pesquisa experimental', 'text' => 'Estrutura adequada para projetos e colaborações científicas.'],
            ['title' => 'Infraestrutura', 'text' => 'Espaço para demonstrar detectores, sistemas e suporte computacional.'],
            ['title' => 'Valor institucional', 'text' => 'Visual mais alinhado ao peso acadêmico da área.'],
        ],
    ],
    989 => [
        'slug' => 'iniciacao-cientifica',
        'title' => 'Iniciação Científica',
        'eyebrow' => 'Oportunidades Acadêmicas',
        'lead' => 'Uma página preparada para orientar estudantes interessados em começar sua trajetória de pesquisa no instituto.',
        'overview' => 'A nova estrutura cria um ponto de entrada mais acolhedor e organizado para informações sobre participação em projetos, acompanhamento docente e desenvolvimento acadêmico.',
        'details' => 'Mesmo sem conteúdo anterior, a página agora já oferece uma base institucional coerente para receber editais, orientações, requisitos, documentos e contatos.',
        'sidebar' => 'A partir daqui, o visitante pode seguir para páginas de programas, corpo docente e demais oportunidades acadêmicas.',
        'sidebarLink' => 'http://localhost/fisica/index.php/corpo-docente/',
        'sidebarCta' => 'Conhecer o corpo docente',
        'highlights' => [
            ['title' => 'Entrada na pesquisa', 'text' => 'Ambiente ideal para apresentar caminhos de iniciação científica.'],
            ['title' => 'Informação clara', 'text' => 'Página pronta para editais, orientações e documentação.'],
            ['title' => 'Experiência acadêmica', 'text' => 'Melhor comunicação para estudantes e futuros candidatos.'],
        ],
    ],
    991 => [
        'slug' => 'monitorias',
        'title' => 'Monitorias',
        'eyebrow' => 'Oportunidades Acadêmicas',
        'lead' => 'Uma página nova para organizar com mais clareza informações sobre monitorias, apoio acadêmico e participação estudantil.',
        'overview' => 'O layout reformulado cria um espaço institucional mais confiável para divulgar editais, regras, orientações, períodos e objetivos das atividades de monitoria.',
        'details' => 'Com essa base visual, a página pode crescer para incluir disciplinas atendidas, critérios de seleção, documentos e canais de contato.',
        'sidebar' => 'A navegação lateral ajuda estudantes a relacionar monitorias com iniciação científica, estágios e outras páginas úteis.',
        'sidebarLink' => 'http://localhost/fisica/index.php/iniciacao-cientifica/',
        'sidebarCta' => 'Ver iniciação científica',
        'highlights' => [
            ['title' => 'Apoio ao ensino', 'text' => 'Espaço para divulgar a função acadêmica das monitorias.'],
            ['title' => 'Orientação ao estudante', 'text' => 'Página pronta para regras, cronogramas e critérios.'],
            ['title' => 'Organização institucional', 'text' => 'Visual mais confiável e profissional.'],
        ],
    ],
    993 => [
        'slug' => 'estagios',
        'title' => 'Estágios',
        'eyebrow' => 'Oportunidades Acadêmicas',
        'lead' => 'Uma base mais profissional para comunicar oportunidades de estágio e integração entre formação acadêmica e experiência prática.',
        'overview' => 'A página agora oferece uma estrutura adequada para inserir orientações, documentos, contatos, requisitos e caminhos institucionais relacionados a estágios.',
        'details' => 'O antigo vazio desta página foi substituído por um ponto de partida claro e funcional, preparado para receber conteúdo definitivo do curso.',
        'sidebar' => 'Use esta navegação para conectar estudantes a páginas de formação, docentes e demais oportunidades disponíveis no instituto.',
        'sidebarLink' => 'http://localhost/fisica/index.php/area-do-aluno/',
        'sidebarCta' => 'Voltar para a área do aluno',
        'highlights' => [
            ['title' => 'Vivência profissional', 'text' => 'Espaço para apresentar estágios e caminhos de atuação.'],
            ['title' => 'Informações práticas', 'text' => 'Página pronta para documentos, regras e contatos.'],
            ['title' => 'Comunicação melhorada', 'text' => 'Visual mais organizado para estudantes e visitantes.'],
        ],
    ],
];

foreach ($pages as $postId => $page) {
    backup_elementor_data($mysqli, $postId, $page['slug']);
    $html = render_detail_html($page);
    $data = [[
        'id' => 'detail_' . $postId,
        'elType' => 'container',
        'settings' => ['content_width' => 'full', 'flex_direction' => 'column'],
        'elements' => [[
            'id' => 'html_' . $postId,
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
    update_meta($mysqli, $postId, '_elementor_template_type', 'wp-page');
    delete_meta($mysqli, $postId, '_elementor_css');
    delete_meta($mysqli, $postId, '_elementor_page_assets');
    delete_meta($mysqli, $postId, '_elementor_element_cache');
    update_post_content($mysqli, $postId, $html);
    echo "Updated {$postId} {$page['title']}\n";
}
?>
