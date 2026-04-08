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
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row) {
        $metaId = (int) $row['meta_id'];
        $stmt = $mysqli->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?");
        $stmt->bind_param('si', $value, $metaId);
        $stmt->execute();
        $stmt->close();
        return;
    }

    $stmt = $mysqli->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $postId, $key, $value);
    $stmt->execute();
    $stmt->close();
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
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    $stmt->close();

    $dir = 'c:/xampp/htdocs/fisica/wp-content/uploads/elementor-db-backups';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $payload = $row[0] ?? '[]';
    file_put_contents($dir . '/' . $name . '-' . date('Ymd-His') . '.json', $payload);
}

function update_post_content(mysqli $mysqli, int $postId, string $content): void {
    $stmt = $mysqli->prepare("UPDATE wp_posts SET post_content = ? WHERE ID = ?");
    $stmt->bind_param('si', $content, $postId);
    $stmt->execute();
    $stmt->close();
}

$homeHeroHtml = <<<'HTML'
<section class="fisica-home-hero">
  <div class="fisica-home-hero__inner">
    <div class="fisica-home-hero__panel">
      <div>
        <span class="fisica-home-hero__eyebrow">Instituto de Física UERJ</span>
        <h1 class="fisica-home-hero__title">Pesquisa, ensino e extensão com uma apresentação mais forte e contemporânea</h1>
        <p class="fisica-home-hero__lead">A nova home organiza melhor o conteúdo principal do instituto, valoriza notícias e cria uma navegação mais clara para laboratórios, programas e oportunidades acadêmicas.</p>
      </div>

      <div class="fisica-home-hero__actions">
        <a class="fisica-home-hero__button fisica-home-hero__button--primary" href="http://localhost/fisica/index.php/contato/">Falar com o instituto</a>
        <a class="fisica-home-hero__button fisica-home-hero__button--secondary" href="http://localhost/fisica/index.php/pessoas/">Conhecer a equipe</a>
      </div>

      <div class="fisica-home-hero__metrics">
        <div class="fisica-home-hero__metric">
          <strong>7</strong>
          <span>laboratórios em destaque na página inicial</span>
        </div>
        <div class="fisica-home-hero__metric">
          <strong>14</strong>
          <span>áreas e programas com acesso direto</span>
        </div>
        <div class="fisica-home-hero__metric">
          <strong>UERJ</strong>
          <span>presença institucional mais sólida e profissional</span>
        </div>
      </div>
    </div>

    <div class="fisica-home-hero__carousel fisica-home-carousel" data-carousel>
      <div class="fisica-home-carousel__track">
        <a class="fisica-home-carousel__slide" href="http://localhost/fisica/noticia/inpe" style="background-image:url('http://localhost/fisica/wp-content/uploads/2026/03/PHOTO-2026-03-18-16-06-52.jpg');">
          <div class="fisica-home-carousel__content">
            <span class="fisica-home-carousel__label">Notícia em destaque</span>
            <h3>Visita técnica ao Instituto Nacional de Pesquisas Espaciais</h3>
            <p>Com professores e estudantes do Instituto de Física da UERJ, a atividade reforçou integração acadêmica, formação e aproximação com centros de excelência.</p>
          </div>
        </a>
        <a class="fisica-home-carousel__slide" href="http://localhost/fisica/noticia/noticia-2" style="background-image:url('http://localhost/fisica/wp-content/uploads/2026/03/PHOTO-2026-03-18-16-24-39.jpg');">
          <div class="fisica-home-carousel__content">
            <span class="fisica-home-carousel__label">Infraestrutura</span>
            <h3>Novos equipamentos para os laboratórios de Mecânica</h3>
            <p>Os investimentos ampliam as condições de ensino experimental e fortalecem a formação prática nos cursos atendidos pelo instituto.</p>
          </div>
        </a>
        <a class="fisica-home-carousel__slide" href="http://localhost/fisica/index.php/calendario-academico/" style="background-image:url('http://localhost/fisica/wp-content/uploads/2025/09/WhatsApp-Image-2025-08-25-at-15.21.17-1.jpg');">
          <div class="fisica-home-carousel__content">
            <span class="fisica-home-carousel__label">Vida acadêmica</span>
            <h3>Agenda, calendário e oportunidades reunidas em uma navegação mais clara</h3>
            <p>O site passa a destacar melhor os caminhos mais acessados por estudantes, professores e visitantes.</p>
          </div>
        </a>
      </div>
      <div class="fisica-home-carousel__controls">
        <button class="fisica-home-carousel__button" type="button" data-carousel-prev aria-label="Slide anterior">&#10094;</button>
        <div class="fisica-home-carousel__dots" data-carousel-dots></div>
        <button class="fisica-home-carousel__button" type="button" data-carousel-next aria-label="Próximo slide">&#10095;</button>
      </div>
    </div>
  </div>
</section>

<script>
(() => {
  const root = document.querySelector('[data-carousel]');
  if (!root || root.dataset.ready === 'true') {
    return;
  }
  root.dataset.ready = 'true';

  const track = root.querySelector('.fisica-home-carousel__track');
  const slides = Array.from(root.querySelectorAll('.fisica-home-carousel__slide'));
  const next = root.querySelector('[data-carousel-next]');
  const prev = root.querySelector('[data-carousel-prev]');
  const dotsWrap = root.querySelector('[data-carousel-dots]');

  if (!track || !slides.length || !next || !prev || !dotsWrap) {
    return;
  }

  let index = 0;
  let timer = null;

  const dots = slides.map((_, i) => {
    const dot = document.createElement('button');
    dot.type = 'button';
    dot.className = 'fisica-home-carousel__dot';
    dot.setAttribute('aria-label', `Ir para slide ${i + 1}`);
    dot.addEventListener('click', () => {
      goTo(i);
      restart();
    });
    dotsWrap.appendChild(dot);
    return dot;
  });

  function render() {
    track.style.transform = `translateX(-${index * 100}%)`;
    dots.forEach((dot, dotIndex) => {
      dot.classList.toggle('is-active', dotIndex === index);
    });
  }

  function goTo(nextIndex) {
    index = (nextIndex + slides.length) % slides.length;
    render();
  }

  function restart() {
    window.clearInterval(timer);
    timer = window.setInterval(() => goTo(index + 1), 5500);
  }

  next.addEventListener('click', () => {
    goTo(index + 1);
    restart();
  });

  prev.addEventListener('click', () => {
    goTo(index - 1);
    restart();
  });

  render();
  restart();
})();
</script>
HTML;

$eventsHtml = <<<'HTML'
<section class="fisica-home-section">
  <div class="fisica-home-section__head">
    <div>
      <span class="fisica-home-section__eyebrow">Eventos e Destaques</span>
      <h2>Acontece no instituto</h2>
      <p>Um bloco mais organizado para divulgar atividades, imagens e acontecimentos relevantes da comunidade acadêmica.</p>
    </div>
  </div>

  <div class="fisica-events-grid">
    <a class="fisica-events-card" href="http://localhost/fisica/wp-content/uploads/2025/09/WhatsApp-Image-2025-08-25-at-15.23.48.jpg" style="background-image:url('http://localhost/fisica/wp-content/uploads/2025/09/WhatsApp-Image-2025-08-25-at-15.23.48.jpg');">
      <div class="fisica-events-card__content">
        <span class="fisica-events-card__tag">Galeria</span>
        <h3>Registro visual das atividades mais recentes</h3>
        <p>Imagens em destaque para comunicar melhor a vida acadêmica, os projetos e os eventos do instituto.</p>
      </div>
    </a>
    <a class="fisica-events-card" href="http://localhost/fisica/wp-content/uploads/2025/09/WhatsApp-Image-2025-08-25-at-15.22.50.jpg" style="background-image:url('http://localhost/fisica/wp-content/uploads/2025/09/WhatsApp-Image-2025-08-25-at-15.22.50.jpg');">
      <div class="fisica-events-card__content">
        <span class="fisica-events-card__tag">Comunidade</span>
        <h3>Projetos, encontros e circulação de conhecimento</h3>
        <p>Uma apresentação visual mais forte para valorizar atividades de ensino, pesquisa, extensão e integração institucional.</p>
      </div>
    </a>
    <a class="fisica-events-card" href="http://localhost/fisica/wp-content/uploads/2025/09/WhatsApp-Image-2025-08-25-at-15.21.17-1.jpg" style="background-image:url('http://localhost/fisica/wp-content/uploads/2025/09/WhatsApp-Image-2025-08-25-at-15.21.17-1.jpg');">
      <div class="fisica-events-card__content">
        <span class="fisica-events-card__tag">Institucional</span>
        <h3>Conteúdo com mais hierarquia e melhor leitura</h3>
        <p>O objetivo desta reformulação é transmitir confiança, clareza e organização já no primeiro contato com o site.</p>
      </div>
    </a>
  </div>
</section>
HTML;

$homeData = [
    [
        'id' => 'homehero1',
        'elType' => 'container',
        'settings' => [
            'content_width' => 'full',
            'flex_direction' => 'column',
            'margin' => ['unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false],
            'padding' => ['unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false],
        ],
        'elements' => [
            [
                'id' => 'homehtml1',
                'elType' => 'widget',
                'widgetType' => 'html',
                'settings' => ['html' => $homeHeroHtml],
                'elements' => [],
            ],
        ],
        'isInner' => false,
    ],
    [
        'id' => 'homeevents1',
        'elType' => 'container',
        'settings' => [
            'content_width' => 'full',
            'flex_direction' => 'column',
        ],
        'elements' => [
            [
                'id' => 'eventhtml1',
                'elType' => 'widget',
                'widgetType' => 'html',
                'settings' => ['html' => $eventsHtml],
                'elements' => [],
            ],
        ],
        'isInner' => false,
    ],
    [
        'id' => 'labsanchor1',
        'elType' => 'container',
        'settings' => ['content_width' => 'full', 'flex_direction' => 'column'],
        'elements' => [
            [
                'id' => 'labshtml1',
                'elType' => 'widget',
                'widgetType' => 'html',
                'settings' => ['html' => '<div id="laboratorios"></div>'],
                'elements' => [],
            ],
            [
                'id' => 'labsshort1',
                'elType' => 'widget',
                'widgetType' => 'shortcode',
                'settings' => ['shortcode' => '[icones_servicos_uerj]'],
                'elements' => [],
            ],
        ],
        'isInner' => false,
    ],
    [
        'id' => 'programanchor1',
        'elType' => 'container',
        'settings' => ['content_width' => 'full', 'flex_direction' => 'column'],
        'elements' => [
            [
                'id' => 'programhtml1',
                'elType' => 'widget',
                'widgetType' => 'html',
                'settings' => ['html' => '<div id="programas"></div>'],
                'elements' => [],
            ],
            [
                'id' => 'programshort1',
                'elType' => 'widget',
                'widgetType' => 'shortcode',
                'settings' => ['shortcode' => '[quadrados_servicos_link]'],
                'elements' => [],
            ],
        ],
        'isInner' => false,
    ],
];

$headerData = [
    [
        'id' => 'fisheader1',
        'elType' => 'container',
        'settings' => [
            '_css_classes' => 'fisica-site-header',
            'flex_direction' => 'row',
            'content_width' => 'boxed',
            'boxed_width' => ['unit' => 'px', 'size' => 1284, 'sizes' => []],
            'min_height' => ['unit' => 'px', 'size' => 92, 'sizes' => []],
            'flex_justify_content' => 'space-between',
            'flex_align_items' => 'center',
            'padding' => ['unit' => 'px', 'top' => '10', 'right' => '18', 'bottom' => '10', 'left' => '18', 'isLinked' => false],
        ],
        'elements' => [
            [
                'id' => 'fisheaderlogo',
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'content_width' => 'full',
                    'width' => ['unit' => '%', 'size' => 24],
                    'width_mobile' => ['unit' => '%', 'size' => 46, 'sizes' => []],
                ],
                'elements' => [
                    [
                        'id' => 'fislogoimg',
                        'elType' => 'widget',
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => [
                                'url' => 'http://localhost/fisica/wp-content/uploads/2025/07/Imagem_do_WhatsApp_de_2025-07-14_a_s__23.42.06_25299ca5-removebg-preview.png',
                                'id' => 47,
                                'size' => '',
                                'alt' => 'Instituto de Física UERJ',
                                'source' => 'library',
                            ],
                            'align' => 'left',
                            'width' => ['unit' => '%', 'size' => 72, 'sizes' => []],
                            'width_mobile' => ['unit' => '%', 'size' => 92, 'sizes' => []],
                            'link_to' => 'custom',
                            'link' => ['url' => 'http://localhost/fisica/', 'is_external' => '', 'nofollow' => '', 'custom_attributes' => ''],
                        ],
                        'elements' => [],
                    ],
                ],
                'isInner' => true,
            ],
            [
                'id' => 'fisheadermenu',
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'width' => ['unit' => '%', 'size' => 76],
                    'width_mobile' => ['unit' => '%', 'size' => 54, 'sizes' => []],
                ],
                'elements' => [
                    [
                        'id' => 'fismenunav',
                        'elType' => 'widget',
                        'widgetType' => 'navigation-menu',
                        'settings' => [
                            'menu' => 'menu',
                            'navmenu_align' => 'right',
                            'resp_align' => 'right',
                            'padding_vertical_menu_item' => ['unit' => 'px', 'size' => 10, 'sizes' => []],
                            'padding_horizontal_menu_item' => ['unit' => 'px', 'size' => 14, 'sizes' => []],
                            'color_menu_item' => '#F4F8FC',
                            'toggle_color' => '#FFFFFF',
                            'menu_typography_typography' => 'custom',
                            'menu_typography_font_family' => 'Montserrat',
                            'menu_typography_font_size' => ['unit' => 'px', 'size' => 16, 'sizes' => []],
                            'menu_typography_font_weight' => '600',
                            'dropdown_divider_border' => 'none',
                            'item_border_border' => 'none',
                            'pointer' => 'underline',
                            'animation_line' => 'yes',
                            'background_color_dropdown_item' => '#0B2F4A',
                            'color_dropdown_item' => '#F4F8FC',
                            'color_dropdown_item_hover' => '#8FCBFF',
                            'color_menu_item_hover' => '#8FCBFF',
                        ],
                        'elements' => [],
                    ],
                ],
                'isInner' => true,
            ],
        ],
        'isInner' => false,
    ],
];

$footerData = [
    [
        'id' => 'fisfooter1',
        'elType' => 'container',
        'settings' => [
            '_css_classes' => 'fisica-site-footer',
            'flex_direction' => 'row',
            'content_width' => 'boxed',
            'boxed_width' => ['unit' => 'px', 'size' => 1284, 'sizes' => []],
            'flex_justify_content' => 'space-between',
            'flex_align_items' => 'center',
            'padding' => ['unit' => 'px', 'top' => '26', 'right' => '18', 'bottom' => '26', 'left' => '18', 'isLinked' => false],
        ],
        'elements' => [
            [
                'id' => 'fisfooterleft',
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'content_width' => 'full',
                    'width' => ['unit' => '%', 'size' => 42],
                ],
                'elements' => [
                    [
                        'id' => 'fisfooterlogo',
                        'elType' => 'widget',
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => [
                                'url' => 'http://localhost/fisica/wp-content/uploads/2025/08/Assinatura-Conjunta-Sem-Tagline-Branco-03.png',
                                'id' => 169,
                                'size' => '',
                                'alt' => 'Instituto de Física UERJ',
                                'source' => 'library',
                            ],
                            'align' => 'left',
                            'width' => ['unit' => '%', 'size' => 52, 'sizes' => []],
                        ],
                        'elements' => [],
                    ],
                    [
                        'id' => 'fisfootertext1',
                        'elType' => 'widget',
                        'widgetType' => 'text-editor',
                        'settings' => [
                            'editor' => '<p>Instituto de Física da UERJ com foco em ensino, pesquisa, extensão e comunicação institucional mais clara para estudantes, docentes e visitantes.</p>',
                            'text_color' => '#DCEBFF',
                            'align' => 'left',
                        ],
                        'elements' => [],
                    ],
                ],
                'isInner' => true,
            ],
            [
                'id' => 'fisfooterright',
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'content_width' => 'full',
                    'width' => ['unit' => '%', 'size' => 58],
                ],
                'elements' => [
                    [
                        'id' => 'fisfootertext2',
                        'elType' => 'widget',
                        'widgetType' => 'text-editor',
                        'settings' => [
                            'editor' => '<p><strong>Endereço</strong><br>R. São Francisco Xavier, 524, Bloco B, Sala 3019, Maracanã, Rio de Janeiro, RJ</p><p><strong>Telefone</strong><br>(21) 2334-0045 | (21) 2334-0071</p><p><strong>Atendimento</strong><br>Segunda a sexta, das 9h às 18h</p>',
                            'text_color' => '#FFFFFF',
                            'align' => 'center',
                        ],
                        'elements' => [],
                    ],
                ],
                'isInner' => true,
            ],
        ],
        'isInner' => false,
    ],
];

$targets = [
    9 => ['name' => 'home', 'data' => $homeData, 'content' => '[icones_servicos_uerj]\n[quadrados_servicos_link]'],
    25 => ['name' => 'header', 'data' => $headerData, 'content' => 'Header do Instituto de Física UERJ'],
    60 => ['name' => 'footer', 'data' => $footerData, 'content' => 'Footer do Instituto de Física UERJ'],
];

foreach ($targets as $postId => $payload) {
    backup_elementor_data($mysqli, $postId, $payload['name']);
    update_meta($mysqli, $postId, '_elementor_data', json_encode($payload['data'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    update_meta($mysqli, $postId, '_elementor_version', '3.32.1');
    update_meta($mysqli, $postId, '_elementor_edit_mode', 'builder');
    delete_meta($mysqli, $postId, '_elementor_css');
    delete_meta($mysqli, $postId, '_elementor_page_assets');
    delete_meta($mysqli, $postId, '_elementor_element_cache');
    update_post_content($mysqli, $postId, $payload['content']);
    echo "Updated post {$postId} ({$payload['name']})\n";
}
?>

