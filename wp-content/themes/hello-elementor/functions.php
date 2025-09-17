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

function meu_shortcode_hello_world() {
	return "<h2 style='color: #0072CE; text-align: center'>Hello World</h2>";
}
add_shortcode('hello_world', 'meu_shortcode_hello_world');

function shortcode_icones_servicos_uerj() {
    $servicos = [
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_achados-e1752004571469.jpeg',
            'desc' => 'Achados e perdidos',
            'tooltip' => 'Perdeu algo? Procure no setor de achados e perdidos',
            'link' => 'https://www.prefeitura.uerj.br/achados-e-perdidos/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_jardinagem.jpg',
            'desc' => 'Áreas verdes e jardinagem',
            'tooltip' => 'Áreas verdes e jardinagem',
            'link' => 'https://www.prefeitura.uerj.br/areas-verdes-e-jardinagem/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_estacionamento-e1752004366566.jpeg',
            'desc' => 'Estacionamento e controle de acesso',
            'tooltip' => 'Gestão de entrada e vagas de veículos',
            'link' => 'https://www.prefeitura.uerj.br/estacionamento-e-controle-de-acesso/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_espacos-e1752004423528.jpeg',
            'desc' => 'Gestão de espaços físicos',
            'tooltip' => 'Gestão de salas e espaços da Prefeitura.',
            'link' => 'https://www.prefeitura.uerj.br/gestao-de-espacos-fisicos/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_limpeza.jpeg',
            'desc' => 'Limpeza e conservação',
            'tooltip' => 'Serviços regulares de limpeza e conservação',
            'link' => 'https://www.prefeitura.uerj.br/limpeza-e-conservacao/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_logistica-e1752008307419.png',
            'desc' => 'Logística e mudança',
            'tooltip' => 'Deslocamento de mobiliário e equipamentos',
            'link' => 'https://www.prefeitura.uerj.br/logistica-e-mudanca/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_manut.jpeg',
            'desc' => 'Manutenção predial',
            'tooltip' => 'Reparos em estrutura, elétrica, hidráulica e telefonia.',
            'link' => 'https://www.prefeitura.uerj.br/manutencao-predial/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_meioambiente.jpeg',
            'desc' => 'Meio ambiente e sustentabilidade',
            'tooltip' => 'Projetos ecológicos e coleta seletiva',
            'link' => 'https://www.prefeitura.uerj.br/meio-ambiente-e-sustentabilidade/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_projetos.jpeg',
            'desc' => 'Projetos de arquitetura e engenharia',
            'tooltip' => 'Planejamento e fiscalização de obras',
            'link' => 'https://www.prefeitura.uerj.br/projetos-de-arquitetura-e-engenharia/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_achados-e1752004571469.jpeg',
            'desc' => 'Correspondências e encomendas',
            'tooltip' => 'Recepção e distribuição de pacotes e cartas',
            'link' => 'https://www.prefeitura.uerj.br/recebimento-de-correspondencia-e-encomendas/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_seguranca-e1752004495417.jpeg',
            'desc' => 'Segurança e vigilância',
            'tooltip' => 'Vigilância dos campi',
            'link' => 'https://www.prefeitura.uerj.br/seguranca-e-vigilancia/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_viaturas-e1751999608121.jpeg',
            'desc' => 'Transportes',
            'tooltip' => 'Solicitação de veículos institucionais',
            'link' => 'https://www.prefeitura.uerj.br/transporte/'
        ],
        [
            'img' => 'https://www.prefeitura.uerj.br/wp-content/uploads/2025/07/serv_portaria-e1752004629152.jpeg',
            'desc' => 'Zeladoria e portaria',
            'tooltip' => 'Apoio geral e atendimento nas portarias',
            'link' => 'https://www.prefeitura.uerj.br/zeladoria-e-portaria/'
        ],
    ];

    ob_start(); ?>
    <div class="grid-linhas-servicos">
        <div class="linha-servicos"><?php for ($i = 0; $i < 4; $i++) echo icone_servico_html($servicos[$i]); ?></div>
        <div class="linha-servicos"><?php for ($i = 4; $i < 8; $i++) echo icone_servico_html($servicos[$i]); ?></div>
        <div class="linha-servicos"><?php for ($i = 8; $i < 12; $i++) echo icone_servico_html($servicos[$i]); ?></div>
        <div class="linha-servicos unica-centralizada"><?php echo icone_servico_html($servicos[12]); ?></div>
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
