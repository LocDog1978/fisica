<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.4.4' );
define( 'EHP_THEME_SLUG', 'hello-elementor' );

define( 'HELLO_THEME_PATH', get_template_directory() );
define( 'HELLO_THEME_URL', get_template_directory_uri() );
define( 'HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/' );
define( 'HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/' );
define( 'HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/' );
define( 'HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/' );
define( 'HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/' );
define( 'HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/' );
define( 'HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/' );
define( 'HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
					'navigation-widgets',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;

		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				HELLO_THEME_STYLE_URL . 'reset.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				HELLO_THEME_STYLE_URL . 'theme.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'hello-elementor-header-footer',
				HELLO_THEME_STYLE_URL . 'header-footer.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	// Customizer controls
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();

/*MEUS CÓDIGOS PHP*/


function shortcode_icones_servicos_uerj() {
    $servicos = [
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/Laboratorios-da-Fisica-03-12-2024-16.jpg',
            'desc' => 'Laboratório de Ensino de Física',
            'tooltip' => 'Conheça nosso Laboratório de Ensino de Fisica',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/Laboratorios-da-Fisica-05-12-2024-11.jpg',
            'desc' => 'Laboratório de Física e Partículas Nuclear',
            'tooltip' => 'Conheça Nosso Laboratório de Física e Partículas Nuclear',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_4052-2.jpg',
            'desc' => 'Laboratório de Física Moderna',
            'tooltip' => 'Conheça Nosso Laboratório de Física Moderna',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_2732-2.jpg',
            'desc' => 'Laboratório de Física Médica',
            'tooltip' => 'Conheça Nosso Laboratório de Física Médica',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_5776-2.jpg',
            'desc' => 'Laboratório HEPGrid (High Energy Physics Grid)',
            'tooltip' => 'Conheça Nosso Laboratório HEPGrid (High Energy Physics Grid)',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_2643-2.jpg',
            'desc' => 'Laboratório de Instrumentação Eletrônica e Técnicas Analíticas - LIETA',
            'tooltip' => 'Conheça Nosso Laboratório de Instrumentação Eletrônica e Técnicas Analíticas - LIETA',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_4437-2.jpg',
            'desc' => 'Laboratório de Física e Eletricidade',
            'tooltip' => 'Conhaça Nosso Laboratório de Física e Eletricidade',
            'link' => ''
        ],
    ];

    ob_start(); ?>
    <div class="grid-linhas-servicos">
        <div class="linha-servicos"><?php for ($i = 0; $i < 4; $i++) echo icone_servico_html($servicos[$i]); ?></div>
        <div class="linha-servicos"><?php for ($i = 4; $i < 7; $i++) echo icone_servico_html($servicos[$i]); ?></div>
        
    </div>
    <?php return ob_get_clean();
}

function icone_servico_html($servico) {
    $img = esc_url($servico['img']);
    $desc = esc_html($servico['desc']);
    $tooltip = esc_attr($servico['tooltip'] ?? $desc);
    $link = esc_url($servico['link'] ?? '#');

    return "
    <div class='icone-servico' data-tooltip=\"{$tooltip}\">
        <a href=\"{$link}\" class=\"icone-link\">
            <div class='circulo-wrapper'>
                <div class='anel-fixo'></div>
                <div class='anel-animado'></div>
                <div class='circulo-interno' style='background-image: url({$img});'></div>
            </div>
        </a>
        <p class='descricao-servico'>{$desc}</p>
    </div>
    ";
}
add_shortcode('icones_servicos_uerj', 'shortcode_icones_servicos_uerj');

function estilos_icones_servicos_uerj() {
    echo '
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .grid-linhas-servicos {
            display: flex;
            flex-direction: column;
            gap: 100px;
            max-width: 1000px;
            margin: 50px auto;
            font-family: "Poppins", sans-serif;
        }

        .linha-servicos {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 50px;
        }

        .linha-servicos.unica-centralizada {
            justify-content: center;
        }

        .icone-servico {
            text-align: center;
            width: 200px;
            position: relative;
        }

        .icone-link {
            text-decoration: none;
            display: block;
        }

        .icone-servico .descricao-servico {
            text-decoration: none;
            margin-top: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #003366;
        }

        .icone-link:hover + .descricao-servico,
        .icone-servico:hover .descricao-servico {
            text-decoration: underline;
        }

        .circulo-wrapper {
            position: relative;
            width: 200px;
            height: 200px;
        }

        .anel-fixo {
            width: 200px;
            height: 200px;
            border: 20px solid #66aaff;
            border-radius: 50%;
            box-sizing: border-box;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .anel-animado {
            width: 200px;
            height: 200px;
            border: 20px solid transparent;
            border-top: 20px solid #003366;
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            opacity: 0;
            pointer-events: none;
            box-sizing: border-box;
            clip-path: inset(0px round 50%);
        }

        .circulo-wrapper:hover .anel-animado {
            animation: girar-circulo 3s linear infinite;
            opacity: 1;
        }

        .circulo-interno {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 3;
        }

        @keyframes girar-circulo {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .icone-servico::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 230px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
            opacity: 0;
            pointer-events: none;
            white-space: nowrap;
            transition: opacity 0.3s ease;
            z-index: 99;
        }

        .icone-servico:hover::after {
            opacity: 1;
        }

        @media (max-width: 1024px) {
            .linha-servicos {
                gap: 30px;
            }
        }

        @media (max-width: 768px) {
            .linha-servicos {
                justify-content: center;
            }
            .icone-servico {
                width: 160px;
            }
            .circulo-wrapper,
            .anel-fixo,
            .anel-animado {
                width: 160px;
                height: 160px;
            }
            .circulo-interno {
                width: 120px;
                height: 120px;
                top: 20px;
                left: 20px;
            }
        }

        @media (max-width: 480px) {
            .linha-servicos {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
    ';
}
add_action('wp_footer', 'estilos_icones_servicos_uerj');


# Código WordPress para Shortcode de Quadrados de Serviços com Modal

// Shortcode para quadrados de serviços com modal
function shortcode_quadrados_servicos_modal() {
    ob_start(); ?>
    
    <div class="grid-quadrados-servicos">
        <div class="linha-quadrados">
            <?php echo quadrado_servico_html(1, 'Laboratório de Ensino', 'Infraestrutura moderna para ensino prático de física', 'modal1'); ?>
            <?php echo quadrado_servico_html(2, 'Física Nuclear', 'Pesquisa avançada em física de partículas nucleares', 'modal2'); ?>
            <?php echo quadrado_servico_html(3, 'Física Moderna', 'Equipamentos de última geração para pesquisas modernas', 'modal3'); ?>
        </div>
        <div class="linha-quadrados">
            <?php echo quadrado_servico_html(4, 'Física Médica', 'Aplicações da física na área médica e saúde', 'modal4'); ?>
            <?php echo quadrado_servico_html(5, 'HEPGrid', 'Infraestrutura computacional para física de alta energia', 'modal5'); ?>
            <?php echo quadrado_servico_html(6, 'LIETA', 'Laboratório de instrumentação eletrônica e técnicas analíticas', 'modal6'); ?>
        </div>
    </div>

    <!-- Modais -->
    <?php echo modal_servico_html('modal1', 'Laboratório de Ensino de Física', 1); ?>
    <?php echo modal_servico_html('modal2', 'Laboratório de Física e Partículas Nuclear', 2); ?>
    <?php echo modal_servico_html('modal3', 'Laboratório de Física Moderna', 3); ?>
    <?php echo modal_servico_html('modal4', 'Laboratório de Física Médica', 4); ?>
    <?php echo modal_servico_html('modal5', 'Laboratório HEPGrid', 5); ?>
    <?php echo modal_servico_html('modal6', 'Laboratório LIETA', 6); ?>
    
    <?php
    return ob_get_clean();
}
add_shortcode('quadrados_servicos_modal', 'shortcode_quadrados_servicos_modal');

// Função para gerar HTML do quadrado
function quadrado_servico_html($numero, $titulo, $descricao, $modal_id) {
    $titulo_esc = esc_html($titulo);
    $descricao_esc = esc_html($descricao);
    $modal_id_esc = esc_attr($modal_id);
    
    return "
    <div class='quadrado-servico' data-modal='{$modal_id_esc}'>
        <div class='quadrado-conteudo'>
            <h3>{$titulo_esc}</h3>
            <p>{$descricao_esc}</p>
        </div>
    </div>
    ";
}

// Função para gerar HTML do modal
function modal_servico_html($modal_id, $titulo, $numero_imagem) {
    $modal_id_esc = esc_attr($modal_id);
    $titulo_esc = esc_html($titulo);
    $imagem_url = esc_url("https://picsum.photos/400/300?random={$numero_imagem}");
    
    // Conteúdo específico para cada modal
    $conteudos = [
        1 => "<p>O Laboratório de Ensino de Física oferece infraestrutura completa para atividades práticas dos cursos de graduação. Equipado com instrumentos modernos e recursos didáticos avançados, proporciona aos estudantes experiência hands-on com os conceitos fundamentais da física.</p>
              <p>Nossas instalações incluem bancadas experimentais, equipamentos de medição digital, kits educacionais e softwares especializados para simulações físicas.</p>",
        2 => "<p>Dedicado à pesquisa avançada em física nuclear e de partículas, este laboratório conta com equipamentos de detecção de radiação, câmaras de nuvens, espectrômetros e sistemas de aquisição de dados de alta velocidade.</p>
              <p>Nossos pesquisadores trabalham em colaboração internacional em projetos como o LHC do CERN, investigando as propriedades fundamentais da matéria e as forças que governam o universo.</p>",
        3 => "<p>Especializado em fenômenos quânticos e relatividade, o Laboratório de Física Moderna oferece equipamentos para experimentos em óptica quântica, criogenia, supercondutividade e física de laser.</p>
              <p>Nossas instalações permitem demonstrações práticas dos conceitos mais avançados da física contemporânea, incluindo interferômetros, sistemas de vácuo e detectores de partículas individuais.</p>",
        4 => "<p>Focado na interface entre física e medicina, este laboratório desenvolve pesquisas em radioterapia, diagnóstico por imagem, proteção radiológica e desenvolvimento de equipamentos médicos.</p>
              <p>Contamos com simuladores de radioterapia, fantomas antropomórficos, sistemas de dosimetria e equipamentos de imageamento médico para pesquisa e desenvolvimento de novas técnicas.</p>",
        5 => "<p>O High Energy Physics Grid Laboratory é um centro de computação de alto desempenho dedicado ao processamento e análise de dados de experiências de física de partículas.</p>
              <p>Nossa infraestrutura inclui clusters computacionais, sistemas de armazenamento massivo e conexão de alta velocidade com redes internacionais de pesquisa, processando petabytes de dados de experiências como ATLAS e CMS.</p>",
        6 => "<p>O Laboratório de Instrumentação Eletrônica e Técnicas Analíticas desenvolve e caracteriza instrumentação científica para diversas aplicações em física e engenharia.</p>
              <p>Nossas competências incluem design de circuitos eletrônicos, desenvolvimento de sistemas de aquisição de dados, técnicas de análise de sinais e implementação de sistemas de controle para experimentos científicos.</p>"
    ];
    
    $conteudo = $conteudos[$numero_imagem] ?? '<p>Conteúdo não disponível.</p>';
    
    return "
    <div id='{$modal_id_esc}' class='modal-servico'>
        <div class='modal-conteudo'>
            <button class='fechar-modal'>&times;</button>
            <h2>{$titulo_esc}</h2>
            <img src='{$imagem_url}' alt='{$titulo_esc}' class='imagem-modal'>
            <div class='texto-modal'>{$conteudo}</div>
        </div>
    </div>
    ";
}

// Adicionar estilos e scripts
function estilos_scripts_quadrados_servicos() {
    echo '
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .grid-quadrados-servicos {
            display: flex;
            flex-direction: column;
            gap: 30px;
            max-width: 1000px;
            margin: 50px auto;
            font-family: "Poppins", sans-serif;
        }

        .linha-quadrados {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .quadrado-servico {
            background: white;
            border: 3px solid #66aaff;
            border-radius: 12px;
            padding: 20px;
            width: 300px;
            min-height: 180px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 51, 102, 0.1);
            position: relative;
            overflow: hidden;
        }

        .quadrado-servico::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 170, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .quadrado-servico:hover::before {
            left: 100%;
        }

        .quadrado-servico:hover {
            transform: translateY(-5px);
            border-color: #003366;
            box-shadow: 0 8px 25px rgba(0, 51, 102, 0.15);
        }

        .quadrado-conteudo {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .quadrado-servico h3 {
            color: #003366;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .quadrado-servico p {
            color: #666;
            font-size: 14px;
            line-height: 1.4;
        }

        /* Estilos do modal */
        .modal-servico {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .modal-servico.active {
            display: flex;
            opacity: 1;
        }

        .modal-conteudo {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: transform 0.3s ease;
            position: relative;
        }

        .modal-servico.active .modal-conteudo {
            transform: scale(1);
        }

        .fechar-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
            color: #666;
        }

        .fechar-modal:hover {
            color: #000;
        }

        .modal-conteudo h2 {
            color: #003366;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .imagem-modal {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .texto-modal p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        @media (max-width: 1024px) {
            .linha-quadrados {
                gap: 15px;
            }
            
            .quadrado-servico {
                width: 280px;
            }
        }

        @media (max-width: 900px) {
            .quadrado-servico {
                width: 100%;
                max-width: 300px;
            }
        }

        @media (max-width: 768px) {
            .linha-quadrados {
                flex-direction: column;
                align-items: center;
            }
            
            .quadrado-servico {
                min-height: 160px;
                padding: 16px;
            }
            
            .quadrado-servico h3 {
                font-size: 16px;
            }
            
            .quadrado-servico p {
                font-size: 13px;
            }
            
            .modal-conteudo {
                padding: 20px;
                margin: 20px;
            }
        }

        @media (max-width: 480px) {
            .grid-quadrados-servicos {
                margin: 30px auto;
            }
            
            .quadrado-servico {
                min-height: 140px;
            }
        }
    </style>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Adicionar eventos de clique aos quadrados
        var quadrados = document.querySelectorAll(".quadrado-servico");
        var modais = document.querySelectorAll(".modal-servico");
        var botoesFechar = document.querySelectorAll(".fechar-modal");
        
        quadrados.forEach(function(quadrado) {
            quadrado.addEventListener("click", function() {
                var modalId = this.getAttribute("data-modal");
                var modal = document.getElementById(modalId);
                
                // Abrir modal
                modal.classList.add("active");
                document.body.style.overflow = "hidden";
            });
        });
        
        // Fechar modais
        botoesFechar.forEach(function(botao) {
            botao.addEventListener("click", function() {
                var modal = this.closest(".modal-servico");
                modal.classList.remove("active");
                document.body.style.overflow = "auto";
            });
        });
        
        // Fechar modal clicando fora do conteúdo
        modais.forEach(function(modal) {
            modal.addEventListener("click", function(e) {
                if (e.target === this) {
                    this.classList.remove("active");
                    document.body.style.overflow = "auto";
                }
            });
        });
        
        // Fechar com ESC
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") {
                modais.forEach(function(modal) {
                    if (modal.classList.contains("active")) {
                        modal.classList.remove("active");
                        document.body.style.overflow = "auto";
                    }
                });
            }
        });
    });
    </script>
    ';
}
add_action('wp_footer', 'estilos_scripts_quadrados_servicos');
