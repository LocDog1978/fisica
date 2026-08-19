<?php
/**
 * Plugin Name: Fisica UERJ - URL do site
 * Description: Centraliza a URL do projeto e normaliza referencias antigas do ambiente local.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'FISICA_SITE_URL' ) ) {
	$fisica_request_host = isset( $_SERVER['HTTP_HOST'] )
		? strtolower( preg_replace( '/:\d+$/', '', $_SERVER['HTTP_HOST'] ) )
		: '';
	$fisica_is_local = in_array( $fisica_request_host, array( 'localhost', '127.0.0.1', '::1' ), true );
	$fisica_env_url  = getenv( 'FISICA_SITE_URL' );
	$fisica_base_url = $fisica_env_url
		? $fisica_env_url
		: ( $fisica_is_local ? 'http://localhost/fisica' : 'https://fisica.uerj.br' );

	define( 'FISICA_SITE_URL', untrailingslashit( $fisica_base_url ) );

	unset( $fisica_request_host, $fisica_is_local, $fisica_env_url, $fisica_base_url );
}

if ( ! function_exists( 'fisica_site_url' ) ) {
	/**
	 * Monta uma URL absoluta a partir da URL canonica do projeto.
	 *
	 * @param string $path Caminho relativo, com ou sem barra inicial.
	 * @return string
	 */
	function fisica_site_url( $path = '' ) {
		$base_url = untrailingslashit( FISICA_SITE_URL );
		$path     = (string) $path;

		if ( '' === $path ) {
			return $base_url . '/';
		}

		if ( '#' === $path[0] || '?' === $path[0] ) {
			return $base_url . '/' . $path;
		}

		return $base_url . '/' . ltrim( $path, '/' );
	}
}

if ( ! function_exists( 'fisica_replace_legacy_local_urls' ) ) {
	/**
	 * Troca URLs absolutas antigas do XAMPP sem modificar o banco de dados.
	 *
	 * @param mixed $value Valor potencialmente contendo uma URL antiga.
	 * @return mixed
	 */
	function fisica_replace_legacy_local_urls( $value ) {
		if ( ! is_string( $value ) || '' === $value ) {
			return $value;
		}

		return str_replace(
			array( 'http://localhost/fisica', 'https://localhost/fisica' ),
			untrailingslashit( FISICA_SITE_URL ),
			$value
		);
	}
}

add_filter( 'the_content', 'fisica_replace_legacy_local_urls', 100 );
add_filter( 'widget_text', 'fisica_replace_legacy_local_urls', 100 );
add_filter( 'widget_text_content', 'fisica_replace_legacy_local_urls', 100 );
add_filter( 'wp_get_attachment_url', 'fisica_replace_legacy_local_urls', 100 );

/**
 * Normaliza links de menus que ainda tenham sido salvos com localhost.
 *
 * @param array $atts Atributos do link do menu.
 * @return array
 */
function fisica_normalize_menu_link_url( $atts ) {
	if ( isset( $atts['href'] ) ) {
		$atts['href'] = fisica_replace_legacy_local_urls( $atts['href'] );
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'fisica_normalize_menu_link_url', 100 );
