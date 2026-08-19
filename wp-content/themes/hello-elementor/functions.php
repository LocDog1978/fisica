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

if ( ! function_exists( 'fisica_resolve_deployed_post_ids' ) ) {
	/**
	 * Resolve local source IDs to their corresponding IDs after a database
	 * content deploy. Local installations transparently keep their original IDs.
	 *
	 * @param int[]  $source_ids    IDs used by the local source database.
	 * @param string $expected_type Optional post type validation.
	 *
	 * @return int[]
	 */
	function fisica_resolve_deployed_post_ids( $source_ids, $expected_type = '' ) {
		global $wpdb;

		static $target_by_source = [];
		static $type_by_source   = [];

		$source_ids = array_values(
			array_unique(
				array_filter(
					array_map( 'intval', (array) $source_ids ),
					static function ( $source_id ) {
						return $source_id > 0;
					}
				)
			)
		);

		$missing_ids = array_values(
			array_filter(
				$source_ids,
				static function ( $source_id ) use ( $target_by_source ) {
					return ! array_key_exists( $source_id, $target_by_source );
				}
			)
		);

		if ( $missing_ids ) {
			$placeholders = implode( ', ', array_fill( 0, count( $missing_ids ), '%d' ) );
			$query        = "SELECT CAST(pm.meta_value AS UNSIGNED) AS source_id, pm.post_id AS target_id, p.post_type
				FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE pm.meta_key = '_fisica_source_id'
				AND CAST(pm.meta_value AS UNSIGNED) IN ({$placeholders})";
			$prepared     = call_user_func_array( [ $wpdb, 'prepare' ], array_merge( [ $query ], $missing_ids ) );
			$rows         = $wpdb->get_results( $prepared, ARRAY_A );

			foreach ( $rows as $row ) {
				$source_id                       = (int) $row['source_id'];
				$target_by_source[ $source_id ] = (int) $row['target_id'];
				$type_by_source[ $source_id ]   = $row['post_type'];
			}

			foreach ( $missing_ids as $source_id ) {
				if ( array_key_exists( $source_id, $target_by_source ) ) {
					continue;
				}

				$source_post = get_post( $source_id );
				if ( $source_post instanceof WP_Post ) {
					$target_by_source[ $source_id ] = $source_id;
					$type_by_source[ $source_id ]   = $source_post->post_type;
				} else {
					$target_by_source[ $source_id ] = 0;
					$type_by_source[ $source_id ]   = '';
				}
			}
		}

		$resolved = [];
		foreach ( $source_ids as $source_id ) {
			if ( $expected_type && $expected_type !== $type_by_source[ $source_id ] ) {
				continue;
			}

			if ( $target_by_source[ $source_id ] > 0 ) {
				$resolved[] = $target_by_source[ $source_id ];
			}
		}

		return $resolved;
	}
}

if ( ! function_exists( 'fisica_resolve_deployed_post_id' ) ) {
	/**
	 * Resolve a single local source ID after a database content deploy.
	 *
	 * @param int    $source_id     Local source ID.
	 * @param string $expected_type Optional post type validation.
	 *
	 * @return int
	 */
	function fisica_resolve_deployed_post_id( $source_id, $expected_type = '' ) {
		$resolved = fisica_resolve_deployed_post_ids( [ $source_id ], $expected_type );
		return isset( $resolved[0] ) ? (int) $resolved[0] : 0;
	}
}

if ( ! function_exists( 'fisica_enqueue_custom_theme_styles' ) ) {
	/**
	 * Enqueue custom visual refinements for local components.
	 *
	 * @return void
	 */
	function fisica_enqueue_custom_theme_styles() {
		$bolsas_page_ids = fisica_resolve_deployed_post_ids( [ 989, 991, 993 ], 'page' );

		wp_enqueue_style(
			'fisica-custom-theme',
			get_stylesheet_directory_uri() . '/assets/css/fisica-custom.css',
			[ 'hello-elementor-theme-style' ],
			filemtime( get_stylesheet_directory() . '/assets/css/fisica-custom.css' )
		);

		wp_add_inline_style(
			'fisica-custom-theme',
			':root{--fisica-rpc-card-image:url("' . esc_url_raw( fisica_site_url( '/wp-content/uploads/2026/07/rpc_2026_card_desktop_4x3.jpg' ) ) . '");}'
		);

		$script_path = get_stylesheet_directory() . '/assets/js/fisica-custom.js';
		$script_url  = get_stylesheet_directory_uri() . '/assets/js/fisica-custom.js';

		if ( file_exists( $script_path ) ) {
			wp_enqueue_script(
				'fisica-custom-theme',
				$script_url,
				[],
				filemtime( $script_path ),
				true
			);

			wp_add_inline_script(
				'fisica-custom-theme',
				'window.fisicaMenuData = ' . wp_json_encode(
					[
						'bolsas' => [
							[
								'label' => 'Iniciação Científica',
								'url'   => get_permalink( $bolsas_page_ids[0] ?? 989 ),
							],
							[
								'label' => 'Monitorias',
								'url'   => get_permalink( $bolsas_page_ids[1] ?? 991 ),
							],
							[
								'label' => 'Estágios',
								'url'   => get_permalink( $bolsas_page_ids[2] ?? 993 ),
							],
						],
					]
				) . '; window.fisicaSiteData = ' . wp_json_encode(
					[
						'siteUrl' => untrailingslashit( FISICA_SITE_URL ),
					]
				) . '; window.fisicaBrandingData = ' . wp_json_encode(
					[
						'uerjLogo' => [
							'id'  => fisica_get_uerj_logo_attachment_id(),
							'url' => fisica_normalize_local_attachment_url_scheme(
								wp_get_attachment_url( fisica_get_uerj_logo_attachment_id() )
							),
							'alt' => get_the_title( fisica_get_uerj_logo_attachment_id() ),
						],
						'footerLogo' => [
							'id'  => fisica_get_footer_logo_attachment_id(),
							'url' => fisica_normalize_local_attachment_url_scheme(
								wp_get_attachment_url( fisica_get_footer_logo_attachment_id() )
							),
						],
					]
				) . ';',
				'before'
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'fisica_enqueue_custom_theme_styles', 20 );

if ( ! function_exists( 'fisica_get_uerj_logo_attachment_id' ) ) {
	/**
	 * Return the institutional UERJ logo attachment id.
	 *
	 * @return int
	 */
	function fisica_get_uerj_logo_attachment_id() {
		return fisica_resolve_deployed_post_id( 1037, 'attachment' );
	}
}

if ( ! function_exists( 'fisica_get_footer_logo_attachment_id' ) ) {
	/**
	 * Return the original footer logo attachment id.
	 *
	 * @return int
	 */
	function fisica_get_footer_logo_attachment_id() {
		return fisica_resolve_deployed_post_id( 169, 'attachment' );
	}
}

if ( ! function_exists( 'fisica_normalize_local_attachment_url_scheme' ) ) {
	/**
	 * Keep localhost attachment URLs aligned with the site's actual scheme.
	 *
	 * @param string $url Attachment URL.
	 *
	 * @return string
	 */
	function fisica_normalize_local_attachment_url_scheme( $url ) {
		if ( ! is_string( $url ) || '' === $url ) {
			return $url;
		}

		$url_host  = wp_parse_url( $url, PHP_URL_HOST );
		$home_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

		if ( ! $url_host || ! $home_host || $url_host !== $home_host ) {
			return $url;
		}

		if ( 'localhost' !== $home_host && '127.0.0.1' !== $home_host ) {
			return $url;
		}

		$scheme = wp_parse_url( home_url( '/' ), PHP_URL_SCHEME );

		return set_url_scheme( $url, $scheme ? $scheme : 'http' );
	}
}
add_filter( 'wp_get_attachment_url', 'fisica_normalize_local_attachment_url_scheme', 20 );

if ( ! function_exists( 'fisica_disable_frontend_cache_on_local' ) ) {
	/**
	 * Reduce browser caching during local development on localhost.
	 *
	 * @return void
	 */
	function fisica_disable_frontend_cache_on_local() {
		if ( is_admin() ) {
			return;
		}

		$host = wp_parse_url( home_url(), PHP_URL_HOST );

		if ( 'localhost' !== $host && '127.0.0.1' !== $host ) {
			return;
		}

		nocache_headers();
		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
	}
}
add_action( 'send_headers', 'fisica_disable_frontend_cache_on_local' );

if ( ! function_exists( 'fisica_disable_wpautop_for_department_pages' ) ) {
	/**
	 * Prevent wpautop from breaking custom HTML layouts on department pages.
	 *
	 * @return void
	 */
	function fisica_disable_wpautop_for_department_pages() {
		$department_page_ids = fisica_resolve_deployed_post_ids( [ 307, 309, 311, 313 ], 'page' );

		if ( is_admin() || ! is_page( $department_page_ids ) ) {
			return;
		}

		remove_filter( 'the_content', 'wpautop' );
	}
}
add_action( 'wp', 'fisica_disable_wpautop_for_department_pages' );

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

/**
 * Translate frontend labels missing from the active pt-BR catalog.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 *
 * @return string
 */
function fisica_translate_frontend_labels_pt_br( $translation, $text, $domain ) {
	if ( 'header-footer-elementor' === $domain && 'Menu Toggle' === $text ) {
		return 'Alternar menu';
	}

	return $translation;
}
add_filter( 'gettext', 'fisica_translate_frontend_labels_pt_br', 10, 3 );

/**
 * Provide meaningful alternative text for the institutional logos.
 *
 * @param array        $attr       Image attributes.
 * @param WP_Post      $attachment Attachment post.
 * @param string|array $size       Requested image size.
 *
 * @return array
 */
function fisica_add_institutional_logo_alt_text( $attr, $attachment, $size ) {
	$institutional_logo_ids = fisica_resolve_deployed_post_ids( [ 47, 169 ], 'attachment' );

	if ( in_array( (int) $attachment->ID, $institutional_logo_ids, true ) && empty( $attr['alt'] ) ) {
		$attr['alt'] = 'Instituto de Física da UERJ';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'fisica_add_institutional_logo_alt_text', 10, 3 );

/* MEUS CÓDIGOS PHP */

/**
 * ======================================================
 *  Sumário de Shortcodes do Tema
 * ======================================================
 * 
 *  1. /shortcodes/01-icones-servicos.php
 *  2. /shortcodes/02-quadrados-servicos-modal.php
 * 
 *  Todos os shortcodes são carregados automaticamente
 *  via include() centralizado.
 */

// Carregar todos os shortcodes dinamicamente
$shortcodes_dir = get_stylesheet_directory() . '/shortcodes/';

foreach (glob($shortcodes_dir . '*.php') as $shortcode_file) {
    include_once $shortcode_file;
}
