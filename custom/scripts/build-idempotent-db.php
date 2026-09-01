<?php
/**
 * Generate an idempotent, content-only SQL release for the Fisica website.
 *
 * This exporter intentionally avoids a full database dump. It deploys pages,
 * Elementor templates, header/footer templates, navigation, attachments and a
 * small allowlist of project-owned options. Environment and runtime data stay
 * under production control.
 */

if ( PHP_SAPI !== 'cli' ) {
	fwrite( STDERR, "Este script deve ser executado pela linha de comando.\n" );
	exit( 1 );
}

$options = getopt(
	'',
	array(
		'output:',
		'target-url::',
		'source-url::',
	)
);

if ( empty( $options['output'] ) ) {
	fwrite( STDERR, "Uso: php build-idempotent-db.php --output=arquivo.sql [--target-url=https://fisica.uerj.br]\n" );
	exit( 1 );
}

$project_root = dirname( __DIR__, 2 );
$output_path  = $options['output'];
$target_url   = isset( $options['target-url'] ) ? rtrim( $options['target-url'], '/' ) : 'https://fisica.uerj.br';
$source_url   = isset( $options['source-url'] ) ? rtrim( $options['source-url'], '/' ) : 'http://localhost/fisica';

if ( ! preg_match( '#^https?://#i', $target_url ) ) {
	fwrite( STDERR, "A URL de producao e invalida: {$target_url}\n" );
	exit( 1 );
}

$_SERVER['HTTP_HOST']   = 'localhost';
$_SERVER['REQUEST_URI'] = '/fisica/';

define( 'WP_USE_THEMES', false );
require $project_root . '/wp-load.php';

global $wpdb;

if ( ! $wpdb instanceof wpdb ) {
	fwrite( STDERR, "Nao foi possivel carregar a conexao do WordPress.\n" );
	exit( 1 );
}

$tables = array(
	'posts'              => $wpdb->posts,
	'postmeta'           => $wpdb->postmeta,
	'terms'              => $wpdb->terms,
	'term_taxonomy'      => $wpdb->term_taxonomy,
	'term_relationships' => $wpdb->term_relationships,
	'options'            => $wpdb->options,
	'users'              => $wpdb->users,
);

$deployable_post_types = array(
	'attachment',
	'page',
	'elementor-hf',
	'elementor_library',
	'wp_global_styles',
	'wp_navigation',
);

$managed_meta_keys = array(
	'_wp_attached_file',
	'_wp_attachment_metadata',
	'_wp_attachment_image_alt',
	'_wp_page_template',
	'_thumbnail_id',
	'_elementor_data',
	'_elementor_edit_mode',
	'_elementor_page_settings',
	'_elementor_template_type',
	'_elementor_version',
	'ehf_target_exclude_locations',
	'ehf_target_include_locations',
	'ehf_target_user_roles',
	'ehf_template_type',
);

$menu_meta_keys = array(
	'_menu_item_type',
	'_menu_item_menu_item_parent',
	'_menu_item_object_id',
	'_menu_item_object',
	'_menu_item_target',
	'_menu_item_classes',
	'_menu_item_xfn',
	'_menu_item_url',
);

/**
 * Quote an SQL identifier that was obtained from WordPress itself.
 */
function fisica_sql_identifier( $identifier ) {
	return '`' . str_replace( '`', '``', $identifier ) . '`';
}

/**
 * Encode text as a binary hex literal. Target text columns convert the UTF-8
 * bytes using their own charset, while comparisons remain independent from
 * the connection and table collations used by each server.
 */
function fisica_sql_text( $value ) {
	if ( null === $value ) {
		return 'NULL';
	}

	$value = (string) $value;

	if ( '' === $value ) {
		return "X''";
	}

	return '0x' . bin2hex( $value );
}

/**
 * Normalize local URLs while preserving PHP serialized values.
 */
function fisica_normalize_value( $value, $source_url, $target_url ) {
	$replace_string = static function ( $text ) use ( $source_url, $target_url ) {
		$source_variants = array(
			$source_url,
			str_replace( 'http://', 'https://', $source_url ),
			str_replace( 'localhost', '127.0.0.1', $source_url ),
			rtrim( $source_url, '/' ) . '/',
		);

		return str_replace( array_unique( $source_variants ), $target_url, $text );
	};

	$normalize_recursive = static function ( $item ) use ( &$normalize_recursive, $replace_string ) {
		if ( is_array( $item ) ) {
			foreach ( $item as $key => $child ) {
				$item[ $key ] = $normalize_recursive( $child );
			}
			return $item;
		}

		if ( is_object( $item ) ) {
			foreach ( get_object_vars( $item ) as $key => $child ) {
				$item->{$key} = $normalize_recursive( $child );
			}
			return $item;
		}

		return is_string( $item ) ? $replace_string( $item ) : $item;
	};

	if ( is_string( $value ) && is_serialized( $value ) ) {
		$decoded = maybe_unserialize( $value );
		return maybe_serialize( $normalize_recursive( $decoded ) );
	}

	return is_string( $value ) ? $replace_string( $value ) : $value;
}

/**
 * Produce an Elementor JSON SQL expression whose numeric resource IDs are
 * replaced after the target database ID map has been built.
 */
function fisica_elementor_expression( $raw_value, $source_url, $target_url, $deployable_ids ) {
	$normalized = fisica_normalize_value( $raw_value, $source_url, $target_url );
	$decoded    = json_decode( $normalized, true );

	if ( ! is_array( $decoded ) ) {
		return fisica_sql_text( $normalized );
	}

	$markers = array();
	$id_keys = array( 'id', 'attachment_id', 'image_id', 'post_id', 'page_id' );

	$walk = static function ( &$node, $parent_key = null ) use ( &$walk, &$markers, $deployable_ids, $id_keys, $source_url, $target_url ) {
		if ( ! is_array( $node ) ) {
			return;
		}

		foreach ( $node as $key => &$value ) {
			if ( is_string( $value ) ) {
				$value = fisica_normalize_value( $value, $source_url, $target_url );
			}

			if (
				in_array( (string) $key, $id_keys, true ) &&
				( is_int( $value ) || ( is_string( $value ) && ctype_digit( $value ) ) )
			) {
				$local_id = (int) $value;
				if ( isset( $deployable_ids[ $local_id ] ) ) {
					$marker               = '__FISICA_DEPLOY_ID_' . $local_id . '__';
					$value                = $marker;
					$markers[ $marker ]   = $local_id;
					continue;
				}
			}

			if ( is_array( $value ) ) {
				$walk( $value, $key );
			}
		}
		unset( $value );
	};

	$walk( $decoded );

	$json = wp_json_encode( $decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	$expr = fisica_sql_text( $json );

	foreach ( $markers as $marker => $local_id ) {
		$quoted_marker = fisica_sql_text( '"' . $marker . '"' );
		$mapped_id     = '(SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . $local_id . ' LIMIT 1)';
		$expr          = 'REPLACE(' . $expr . ', ' . $quoted_marker . ', CAST(' . $mapped_id . ' AS CHAR))';
	}

	return $expr;
}

/**
 * Return all local post meta rows indexed by post ID and key.
 */
function fisica_index_meta_rows( $rows ) {
	$indexed = array();

	foreach ( $rows as $row ) {
		$post_id = (int) $row['post_id'];
		$key     = $row['meta_key'];
		if ( ! isset( $indexed[ $post_id ] ) ) {
			$indexed[ $post_id ] = array();
		}
		if ( ! isset( $indexed[ $post_id ][ $key ] ) ) {
			$indexed[ $post_id ][ $key ] = array();
		}
		$indexed[ $post_id ][ $key ][] = $row['meta_value'];
	}

	return $indexed;
}

/**
 * Read the first value of a WordPress meta key.
 */
function fisica_first_meta( $indexed, $post_id, $key, $default = '' ) {
	return isset( $indexed[ $post_id ][ $key ][0] ) ? $indexed[ $post_id ][ $key ][0] : $default;
}

/**
 * Create a stable semantic key for a normal deployable post.
 */
function fisica_post_deploy_key( $post, $attached_file = '' ) {
	if ( 'attachment' === $post['post_type'] && '' !== $attached_file ) {
		return 'attachment:' . ltrim( str_replace( '\\', '/', $attached_file ), '/' );
	}

	return 'post:' . $post['post_type'] . ':' . $post['post_name'];
}

/**
 * Build a stable key for a menu item using its semantic ancestry.
 */
function fisica_menu_item_key( $item_id, $menu_slug, $items_by_id, $meta, $stable_by_id, $source_url, $target_url, &$cache ) {
	if ( isset( $cache[ $item_id ] ) ) {
		return $cache[ $item_id ];
	}

	$item      = $items_by_id[ $item_id ];
	$type      = fisica_first_meta( $meta, $item_id, '_menu_item_type' );
	$object    = fisica_first_meta( $meta, $item_id, '_menu_item_object' );
	$object_id = (int) fisica_first_meta( $meta, $item_id, '_menu_item_object_id', '0' );
	$parent_id = (int) fisica_first_meta( $meta, $item_id, '_menu_item_menu_item_parent', '0' );
	$url       = fisica_normalize_value( fisica_first_meta( $meta, $item_id, '_menu_item_url' ), $source_url, $target_url );

	if ( 'post_type' === $type && isset( $stable_by_id[ $object_id ] ) ) {
		$semantic = $type . ':' . $object . ':' . $stable_by_id[ $object_id ];
	} else {
		$semantic = $type . ':' . $object . ':' . $url . ':' . $item['post_title'];
	}

	$parent_path = '';
	if ( $parent_id > 0 && isset( $items_by_id[ $parent_id ] ) ) {
		$parent_path = fisica_menu_item_key( $parent_id, $menu_slug, $items_by_id, $meta, $stable_by_id, $source_url, $target_url, $cache ) . '/';
	}

	$cache[ $item_id ] = 'nav-menu-item:' . $menu_slug . ':' . hash( 'sha256', $parent_path . $semantic );
	return $cache[ $item_id ];
}

$post_type_placeholders = implode( ',', array_fill( 0, count( $deployable_post_types ), '%s' ) );
$posts_sql              = $wpdb->prepare(
	"SELECT * FROM {$wpdb->posts}
	 WHERE post_type IN ({$post_type_placeholders})
	   AND post_status NOT IN ('trash', 'auto-draft')
	 ORDER BY CASE WHEN post_type = 'attachment' THEN 0 ELSE 1 END, ID",
	$deployable_post_types
);
$posts                  = $wpdb->get_results( $posts_sql, ARRAY_A );

if ( ! is_array( $posts ) ) {
	fwrite( STDERR, "Falha ao consultar os conteudos do WordPress.\n" );
	exit( 1 );
}

$core_ids = array_map(
	static function ( $post ) {
		return (int) $post['ID'];
	},
	$posts
);

$menu_items = $wpdb->get_results(
	"SELECT * FROM {$wpdb->posts} WHERE post_type = 'nav_menu_item' AND post_status NOT IN ('trash', 'auto-draft') ORDER BY menu_order, ID",
	ARRAY_A
);
$menu_ids   = array_map(
	static function ( $post ) {
		return (int) $post['ID'];
	},
	$menu_items
);
$all_ids    = array_merge( $core_ids, $menu_ids );

$meta_rows = array();
if ( $all_ids ) {
	$id_list   = implode( ',', array_map( 'intval', $all_ids ) );
	$meta_rows = $wpdb->get_results(
		"SELECT post_id, meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id IN ({$id_list}) ORDER BY meta_id",
		ARRAY_A
	);
}
$meta = fisica_index_meta_rows( $meta_rows );

$posts_by_id   = array();
$stable_by_id  = array();
$deployable_ids = array();

foreach ( $posts as $post ) {
	$post_id       = (int) $post['ID'];
	$attached_file = fisica_first_meta( $meta, $post_id, '_wp_attached_file' );
	$stable_key    = fisica_post_deploy_key( $post, $attached_file );
	$posts_by_id[ $post_id ]  = $post;
	$stable_by_id[ $post_id ] = $stable_key;
	$deployable_ids[ $post_id ] = true;
}

foreach ( $menu_ids as $menu_id ) {
	$deployable_ids[ $menu_id ] = true;
}

$q = static function ( $name ) use ( $tables ) {
	return fisica_sql_identifier( $tables[ $name ] );
};

$sql       = array();
$timestamp = gmdate( 'Y-m-d H:i:s' );
$release   = hash( 'sha256', $timestamp . '|' . count( $posts ) . '|' . count( $meta_rows ) . '|' . $target_url );

$sql[] = '-- Deploy de conteudo idempotente do Instituto de Fisica da UERJ';
$sql[] = '-- Gerado em UTC: ' . $timestamp;
$sql[] = '-- URL de destino: ' . $target_url;
$sql[] = '-- Este arquivo NAO altera siteurl, home, usuarios, comentarios, credenciais, plugins ativos ou opcoes de ambiente.';
$sql[] = '-- Execute primeiro em homologacao e mantenha um backup completo do banco de producao.';
$sql[] = '';
$sql[] = 'SET NAMES utf8mb4;';
$sql[] = 'SET @fisica_old_sql_safe_updates := @@SQL_SAFE_UPDATES;';
$sql[] = 'SET SQL_SAFE_UPDATES = 0;';
$sql[] = 'START TRANSACTION;';
$sql[] = '';
$sql[] = 'CREATE TEMPORARY TABLE IF NOT EXISTS `tmp_fisica_deploy_id_map` (';
$sql[] = '  `local_id` BIGINT UNSIGNED NOT NULL PRIMARY KEY,';
$sql[] = '  `target_id` BIGINT UNSIGNED NOT NULL,';
$sql[] = '  `entity_type` VARCHAR(32) NOT NULL,';
$sql[] = '  `deploy_key` VARCHAR(255) NOT NULL,';
$sql[] = '  KEY `target_id` (`target_id`)';
$sql[] = ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
$sql[] = 'CREATE TEMPORARY TABLE IF NOT EXISTS `tmp_fisica_deploy_term_map` (';
$sql[] = '  `local_term_taxonomy_id` BIGINT UNSIGNED NOT NULL PRIMARY KEY,';
$sql[] = '  `target_term_id` BIGINT UNSIGNED NOT NULL,';
$sql[] = '  `target_term_taxonomy_id` BIGINT UNSIGNED NOT NULL,';
$sql[] = '  `slug` VARCHAR(200) NOT NULL';
$sql[] = ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
$sql[] = '';

$post_insert_columns = array(
	'post_author',
	'post_date',
	'post_date_gmt',
	'post_content',
	'post_title',
	'post_excerpt',
	'post_status',
	'comment_status',
	'ping_status',
	'post_password',
	'post_name',
	'to_ping',
	'pinged',
	'post_modified',
	'post_modified_gmt',
	'post_content_filtered',
	'post_parent',
	'guid',
	'menu_order',
	'post_type',
	'post_mime_type',
	'comment_count',
);

foreach ( $posts as $post ) {
	$local_id      = (int) $post['ID'];
	$attached_file = fisica_first_meta( $meta, $local_id, '_wp_attached_file' );
	$deploy_key    = $stable_by_id[ $local_id ];
	$post_content  = fisica_normalize_value( $post['post_content'], $source_url, $target_url );
	$guid          = fisica_normalize_value( $post['guid'], $source_url, $target_url );

	$sql[] = '-- ' . $deploy_key;
	$sql[] = 'SET @fisica_id := (SELECT `post_id` FROM ' . $q( 'postmeta' ) . ' WHERE `meta_key` = ' . fisica_sql_text( '_fisica_deploy_key' ) . ' AND `meta_value` = ' . fisica_sql_text( $deploy_key ) . ' ORDER BY `post_id` LIMIT 1);';

	if ( 'attachment' === $post['post_type'] && '' !== $attached_file ) {
		$sql[] = 'SET @fisica_id := COALESCE(@fisica_id, (SELECT `post_id` FROM ' . $q( 'postmeta' ) . ' WHERE `meta_key` = ' . fisica_sql_text( '_wp_attached_file' ) . ' AND `meta_value` = ' . fisica_sql_text( $attached_file ) . ' ORDER BY `post_id` LIMIT 1));';
	}

	$sql[] = 'SET @fisica_id := COALESCE(@fisica_id, (SELECT `ID` FROM ' . $q( 'posts' ) . ' WHERE `post_type` = ' . fisica_sql_text( $post['post_type'] ) . ' AND `post_name` = ' . fisica_sql_text( $post['post_name'] ) . ' ORDER BY `ID` LIMIT 1));';

	$insert_values = array(
		'COALESCE((SELECT MIN(`ID`) FROM ' . $q( 'users' ) . '), 0)',
		fisica_sql_text( $post['post_date'] ),
		fisica_sql_text( $post['post_date_gmt'] ),
		fisica_sql_text( $post_content ),
		fisica_sql_text( $post['post_title'] ),
		fisica_sql_text( $post['post_excerpt'] ),
		fisica_sql_text( $post['post_status'] ),
		fisica_sql_text( $post['comment_status'] ),
		fisica_sql_text( $post['ping_status'] ),
		fisica_sql_text( $post['post_password'] ),
		fisica_sql_text( $post['post_name'] ),
		fisica_sql_text( $post['to_ping'] ),
		fisica_sql_text( $post['pinged'] ),
		fisica_sql_text( $post['post_modified'] ),
		fisica_sql_text( $post['post_modified_gmt'] ),
		fisica_sql_text( $post['post_content_filtered'] ),
		'0',
		fisica_sql_text( $guid ),
		(int) $post['menu_order'],
		fisica_sql_text( $post['post_type'] ),
		fisica_sql_text( $post['post_mime_type'] ),
		'0',
	);

	$sql[] = 'INSERT INTO ' . $q( 'posts' ) . ' (`' . implode( '`, `', $post_insert_columns ) . '`) SELECT ' . implode( ', ', $insert_values ) . ' WHERE @fisica_id IS NULL;';
	$sql[] = 'SET @fisica_id := IF(@fisica_id IS NULL, LAST_INSERT_ID(), @fisica_id);';

	$updates = array(
		'`post_date` = ' . fisica_sql_text( $post['post_date'] ),
		'`post_date_gmt` = ' . fisica_sql_text( $post['post_date_gmt'] ),
		'`post_content` = ' . fisica_sql_text( $post_content ),
		'`post_title` = ' . fisica_sql_text( $post['post_title'] ),
		'`post_excerpt` = ' . fisica_sql_text( $post['post_excerpt'] ),
		'`post_status` = ' . fisica_sql_text( $post['post_status'] ),
		'`comment_status` = ' . fisica_sql_text( $post['comment_status'] ),
		'`ping_status` = ' . fisica_sql_text( $post['ping_status'] ),
		'`post_password` = ' . fisica_sql_text( $post['post_password'] ),
		'`post_name` = ' . fisica_sql_text( $post['post_name'] ),
		'`to_ping` = ' . fisica_sql_text( $post['to_ping'] ),
		'`pinged` = ' . fisica_sql_text( $post['pinged'] ),
		'`post_modified` = ' . fisica_sql_text( $post['post_modified'] ),
		'`post_modified_gmt` = ' . fisica_sql_text( $post['post_modified_gmt'] ),
		'`post_content_filtered` = ' . fisica_sql_text( $post['post_content_filtered'] ),
		'`menu_order` = ' . (int) $post['menu_order'],
		'`post_mime_type` = ' . fisica_sql_text( $post['post_mime_type'] ),
	);
	$sql[] = 'UPDATE ' . $q( 'posts' ) . ' SET ' . implode( ', ', $updates ) . ' WHERE `ID` = @fisica_id;';
	$sql[] = 'DELETE FROM ' . $q( 'postmeta' ) . ' WHERE `post_id` = @fisica_id AND `meta_key` = ' . fisica_sql_text( '_fisica_deploy_key' ) . ';';
	$sql[] = 'INSERT INTO ' . $q( 'postmeta' ) . ' (`post_id`, `meta_key`, `meta_value`) VALUES (@fisica_id, ' . fisica_sql_text( '_fisica_deploy_key' ) . ', ' . fisica_sql_text( $deploy_key ) . ');';
	$sql[] = 'DELETE FROM ' . $q( 'postmeta' ) . ' WHERE `post_id` = @fisica_id AND `meta_key` = ' . fisica_sql_text( '_fisica_source_id' ) . ';';
	$sql[] = 'INSERT INTO ' . $q( 'postmeta' ) . ' (`post_id`, `meta_key`, `meta_value`) VALUES (@fisica_id, ' . fisica_sql_text( '_fisica_source_id' ) . ', ' . fisica_sql_text( (string) $local_id ) . ');';
	$sql[] = 'INSERT INTO `tmp_fisica_deploy_id_map` (`local_id`, `target_id`, `entity_type`, `deploy_key`) VALUES (' . $local_id . ', @fisica_id, ' . fisica_sql_text( $post['post_type'] ) . ', ' . fisica_sql_text( $deploy_key ) . ') ON DUPLICATE KEY UPDATE `target_id` = VALUES(`target_id`), `entity_type` = VALUES(`entity_type`), `deploy_key` = VALUES(`deploy_key`);';
	$sql[] = '';
}

foreach ( $posts as $post ) {
	$local_id       = (int) $post['ID'];
	$local_parent   = (int) $post['post_parent'];
	$target_id_expr = '(SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . $local_id . ')';
	$parent_expr    = $local_parent > 0
		? 'COALESCE((SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . $local_parent . '), 0)'
		: '0';
	$sql[] = 'UPDATE ' . $q( 'posts' ) . ' SET `post_parent` = ' . $parent_expr . ' WHERE `ID` = ' . $target_id_expr . ';';
}
$sql[] = '';

$managed_meta_list = implode( ', ', array_map( 'fisica_sql_text', $managed_meta_keys ) );

foreach ( $posts as $post ) {
	$local_id       = (int) $post['ID'];
	$target_id_expr = '(SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . $local_id . ')';
	$sql[]          = '-- Metadados gerenciados de ' . $stable_by_id[ $local_id ];
	$sql[]          = 'DELETE FROM ' . $q( 'postmeta' ) . ' WHERE `post_id` = ' . $target_id_expr . ' AND `meta_key` IN (' . $managed_meta_list . ');';

	foreach ( $managed_meta_keys as $meta_key ) {
		if ( empty( $meta[ $local_id ][ $meta_key ] ) ) {
			continue;
		}

		foreach ( $meta[ $local_id ][ $meta_key ] as $meta_value ) {
			if ( '_thumbnail_id' === $meta_key ) {
				$referenced_id = (int) $meta_value;
				$value_expr    = 'COALESCE((SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . $referenced_id . '), 0)';
			} elseif ( '_elementor_data' === $meta_key ) {
				$value_expr = fisica_elementor_expression( $meta_value, $source_url, $target_url, $deployable_ids );
			} else {
				$value_expr = fisica_sql_text( fisica_normalize_value( $meta_value, $source_url, $target_url ) );
			}

			$sql[] = 'INSERT INTO ' . $q( 'postmeta' ) . ' (`post_id`, `meta_key`, `meta_value`) VALUES (' . $target_id_expr . ', ' . fisica_sql_text( $meta_key ) . ', ' . $value_expr . ');';
		}
	}

	$sql[] = '';
}

$nav_terms = $wpdb->get_results(
	"SELECT t.term_id, t.name, t.slug, t.term_group, tt.term_taxonomy_id, tt.description, tt.parent
	 FROM {$wpdb->terms} t
	 INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id
	 WHERE tt.taxonomy = 'nav_menu'
	 ORDER BY t.term_id",
	ARRAY_A
);

foreach ( $nav_terms as $term ) {
	$local_tt_id = (int) $term['term_taxonomy_id'];
	$sql[] = '-- Menu: ' . $term['slug'];
	$sql[] = 'SET @fisica_term_id := (SELECT `term_id` FROM ' . $q( 'terms' ) . ' WHERE `slug` = ' . fisica_sql_text( $term['slug'] ) . ' ORDER BY `term_id` LIMIT 1);';
	$sql[] = 'INSERT INTO ' . $q( 'terms' ) . ' (`name`, `slug`, `term_group`) SELECT ' . fisica_sql_text( $term['name'] ) . ', ' . fisica_sql_text( $term['slug'] ) . ', ' . (int) $term['term_group'] . ' WHERE @fisica_term_id IS NULL;';
	$sql[] = 'SET @fisica_term_id := IF(@fisica_term_id IS NULL, LAST_INSERT_ID(), @fisica_term_id);';
	$sql[] = 'UPDATE ' . $q( 'terms' ) . ' SET `name` = ' . fisica_sql_text( $term['name'] ) . ', `slug` = ' . fisica_sql_text( $term['slug'] ) . ', `term_group` = ' . (int) $term['term_group'] . ' WHERE `term_id` = @fisica_term_id;';
	$sql[] = 'SET @fisica_tt_id := (SELECT `term_taxonomy_id` FROM ' . $q( 'term_taxonomy' ) . ' WHERE `term_id` = @fisica_term_id AND `taxonomy` = ' . fisica_sql_text( 'nav_menu' ) . ' ORDER BY `term_taxonomy_id` LIMIT 1);';
	$sql[] = 'INSERT INTO ' . $q( 'term_taxonomy' ) . ' (`term_id`, `taxonomy`, `description`, `parent`, `count`) SELECT @fisica_term_id, ' . fisica_sql_text( 'nav_menu' ) . ', ' . fisica_sql_text( $term['description'] ) . ', 0, 0 WHERE @fisica_tt_id IS NULL;';
	$sql[] = 'SET @fisica_tt_id := IF(@fisica_tt_id IS NULL, LAST_INSERT_ID(), @fisica_tt_id);';
	$sql[] = 'UPDATE ' . $q( 'term_taxonomy' ) . ' SET `description` = ' . fisica_sql_text( $term['description'] ) . ' WHERE `term_taxonomy_id` = @fisica_tt_id;';
	$sql[] = 'INSERT INTO `tmp_fisica_deploy_term_map` (`local_term_taxonomy_id`, `target_term_id`, `target_term_taxonomy_id`, `slug`) VALUES (' . $local_tt_id . ', @fisica_term_id, @fisica_tt_id, ' . fisica_sql_text( $term['slug'] ) . ') ON DUPLICATE KEY UPDATE `target_term_id` = VALUES(`target_term_id`), `target_term_taxonomy_id` = VALUES(`target_term_taxonomy_id`), `slug` = VALUES(`slug`);';
	$sql[] = '';
}

$menu_relationship_rows = $wpdb->get_results(
	"SELECT tr.object_id, tr.term_taxonomy_id, tr.term_order, t.slug
	 FROM {$wpdb->term_relationships} tr
	 INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy = 'nav_menu'
	 INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id",
	ARRAY_A
);
$menu_by_item = array();
foreach ( $menu_relationship_rows as $relationship ) {
	$menu_by_item[ (int) $relationship['object_id'] ] = $relationship;
}

$items_by_id = array();
foreach ( $menu_items as $item ) {
	$items_by_id[ (int) $item['ID'] ] = $item;
}

$menu_key_cache = array();

foreach ( $menu_items as $item ) {
	$local_id = (int) $item['ID'];
	if ( empty( $menu_by_item[ $local_id ] ) ) {
		continue;
	}

	$relationship = $menu_by_item[ $local_id ];
	$menu_slug    = $relationship['slug'];
	$deploy_key   = fisica_menu_item_key( $local_id, $menu_slug, $items_by_id, $meta, $stable_by_id, $source_url, $target_url, $menu_key_cache );
	$type         = fisica_first_meta( $meta, $local_id, '_menu_item_type' );
	$object       = fisica_first_meta( $meta, $local_id, '_menu_item_object' );
	$object_id    = (int) fisica_first_meta( $meta, $local_id, '_menu_item_object_id', '0' );
	$url          = fisica_normalize_value( fisica_first_meta( $meta, $local_id, '_menu_item_url' ), $source_url, $target_url );
	$content      = fisica_normalize_value( $item['post_content'], $source_url, $target_url );

	$sql[] = '-- ' . $deploy_key;
	$sql[] = 'SET @fisica_menu_item_id := (SELECT `post_id` FROM ' . $q( 'postmeta' ) . ' WHERE `meta_key` = ' . fisica_sql_text( '_fisica_deploy_key' ) . ' AND `meta_value` = ' . fisica_sql_text( $deploy_key ) . ' ORDER BY `post_id` LIMIT 1);';

	if ( 'post_type' === $type && isset( $stable_by_id[ $object_id ] ) ) {
		$mapped_object = '(SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . $object_id . ')';
		$mapped_tt     = '(SELECT `target_term_taxonomy_id` FROM `tmp_fisica_deploy_term_map` WHERE `local_term_taxonomy_id` = ' . (int) $relationship['term_taxonomy_id'] . ')';
		$sql[] = 'SET @fisica_menu_item_id := COALESCE(@fisica_menu_item_id, (SELECT p.`ID` FROM ' . $q( 'posts' ) . ' p INNER JOIN ' . $q( 'postmeta' ) . ' mt ON mt.`post_id` = p.`ID` AND mt.`meta_key` = ' . fisica_sql_text( '_menu_item_type' ) . ' AND mt.`meta_value` = ' . fisica_sql_text( $type ) . ' INNER JOIN ' . $q( 'postmeta' ) . ' mo ON mo.`post_id` = p.`ID` AND mo.`meta_key` = ' . fisica_sql_text( '_menu_item_object' ) . ' AND mo.`meta_value` = ' . fisica_sql_text( $object ) . ' INNER JOIN ' . $q( 'postmeta' ) . ' mi ON mi.`post_id` = p.`ID` AND mi.`meta_key` = ' . fisica_sql_text( '_menu_item_object_id' ) . ' AND CAST(mi.`meta_value` AS UNSIGNED) = ' . $mapped_object . ' INNER JOIN ' . $q( 'term_relationships' ) . ' tr ON tr.`object_id` = p.`ID` AND tr.`term_taxonomy_id` = ' . $mapped_tt . ' WHERE p.`post_type` = ' . fisica_sql_text( 'nav_menu_item' ) . ' ORDER BY p.`ID` LIMIT 1));';
	} else {
		$mapped_tt = '(SELECT `target_term_taxonomy_id` FROM `tmp_fisica_deploy_term_map` WHERE `local_term_taxonomy_id` = ' . (int) $relationship['term_taxonomy_id'] . ')';
		$sql[] = 'SET @fisica_menu_item_id := COALESCE(@fisica_menu_item_id, (SELECT p.`ID` FROM ' . $q( 'posts' ) . ' p INNER JOIN ' . $q( 'postmeta' ) . ' mt ON mt.`post_id` = p.`ID` AND mt.`meta_key` = ' . fisica_sql_text( '_menu_item_type' ) . ' AND mt.`meta_value` = ' . fisica_sql_text( $type ) . ' INNER JOIN ' . $q( 'postmeta' ) . ' mu ON mu.`post_id` = p.`ID` AND mu.`meta_key` = ' . fisica_sql_text( '_menu_item_url' ) . ' AND mu.`meta_value` = ' . fisica_sql_text( $url ) . ' INNER JOIN ' . $q( 'term_relationships' ) . ' tr ON tr.`object_id` = p.`ID` AND tr.`term_taxonomy_id` = ' . $mapped_tt . ' WHERE p.`post_type` = ' . fisica_sql_text( 'nav_menu_item' ) . ' AND p.`post_title` = ' . fisica_sql_text( $item['post_title'] ) . ' ORDER BY p.`ID` LIMIT 1));';
	}

	$sql[] = 'SET @fisica_menu_item_id := COALESCE(@fisica_menu_item_id, (SELECT `ID` FROM ' . $q( 'posts' ) . ' WHERE `ID` = ' . $local_id . ' AND `post_type` = ' . fisica_sql_text( 'nav_menu_item' ) . ' LIMIT 1));';

	$insert_values = array(
		'COALESCE((SELECT MIN(`ID`) FROM ' . $q( 'users' ) . '), 0)',
		fisica_sql_text( $item['post_date'] ),
		fisica_sql_text( $item['post_date_gmt'] ),
		fisica_sql_text( $content ),
		fisica_sql_text( $item['post_title'] ),
		fisica_sql_text( $item['post_excerpt'] ),
		fisica_sql_text( $item['post_status'] ),
		fisica_sql_text( $item['comment_status'] ),
		fisica_sql_text( $item['ping_status'] ),
		fisica_sql_text( $item['post_password'] ),
		fisica_sql_text( $item['post_name'] ),
		fisica_sql_text( $item['to_ping'] ),
		fisica_sql_text( $item['pinged'] ),
		fisica_sql_text( $item['post_modified'] ),
		fisica_sql_text( $item['post_modified_gmt'] ),
		fisica_sql_text( $item['post_content_filtered'] ),
		'0',
		fisica_sql_text( fisica_normalize_value( $item['guid'], $source_url, $target_url ) ),
		(int) $item['menu_order'],
		fisica_sql_text( 'nav_menu_item' ),
		fisica_sql_text( $item['post_mime_type'] ),
		'0',
	);

	$sql[] = 'INSERT INTO ' . $q( 'posts' ) . ' (`' . implode( '`, `', $post_insert_columns ) . '`) SELECT ' . implode( ', ', $insert_values ) . ' WHERE @fisica_menu_item_id IS NULL;';
	$sql[] = 'SET @fisica_menu_item_id := IF(@fisica_menu_item_id IS NULL, LAST_INSERT_ID(), @fisica_menu_item_id);';
	$sql[] = 'UPDATE ' . $q( 'posts' ) . ' SET `post_content` = ' . fisica_sql_text( $content ) . ', `post_title` = ' . fisica_sql_text( $item['post_title'] ) . ', `post_excerpt` = ' . fisica_sql_text( $item['post_excerpt'] ) . ', `post_status` = ' . fisica_sql_text( $item['post_status'] ) . ', `post_name` = ' . fisica_sql_text( $item['post_name'] ) . ', `menu_order` = ' . (int) $item['menu_order'] . ' WHERE `ID` = @fisica_menu_item_id;';
	$sql[] = 'DELETE FROM ' . $q( 'postmeta' ) . ' WHERE `post_id` = @fisica_menu_item_id AND `meta_key` = ' . fisica_sql_text( '_fisica_deploy_key' ) . ';';
	$sql[] = 'INSERT INTO ' . $q( 'postmeta' ) . ' (`post_id`, `meta_key`, `meta_value`) VALUES (@fisica_menu_item_id, ' . fisica_sql_text( '_fisica_deploy_key' ) . ', ' . fisica_sql_text( $deploy_key ) . ');';
	$sql[] = 'DELETE FROM ' . $q( 'postmeta' ) . ' WHERE `post_id` = @fisica_menu_item_id AND `meta_key` = ' . fisica_sql_text( '_fisica_source_id' ) . ';';
	$sql[] = 'INSERT INTO ' . $q( 'postmeta' ) . ' (`post_id`, `meta_key`, `meta_value`) VALUES (@fisica_menu_item_id, ' . fisica_sql_text( '_fisica_source_id' ) . ', ' . fisica_sql_text( (string) $local_id ) . ');';
	$sql[] = 'INSERT INTO `tmp_fisica_deploy_id_map` (`local_id`, `target_id`, `entity_type`, `deploy_key`) VALUES (' . $local_id . ', @fisica_menu_item_id, ' . fisica_sql_text( 'nav_menu_item' ) . ', ' . fisica_sql_text( $deploy_key ) . ') ON DUPLICATE KEY UPDATE `target_id` = VALUES(`target_id`), `entity_type` = VALUES(`entity_type`), `deploy_key` = VALUES(`deploy_key`);';
	$sql[] = '';
}

$menu_meta_list = implode( ', ', array_map( 'fisica_sql_text', $menu_meta_keys ) );

foreach ( $menu_items as $item ) {
	$local_id = (int) $item['ID'];
	if ( empty( $menu_by_item[ $local_id ] ) ) {
		continue;
	}

	$relationship  = $menu_by_item[ $local_id ];
	$target_id_expr = '(SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . $local_id . ')';
	$type            = fisica_first_meta( $meta, $local_id, '_menu_item_type' );
	$object_id       = (int) fisica_first_meta( $meta, $local_id, '_menu_item_object_id', '0' );
	$parent_id       = (int) fisica_first_meta( $meta, $local_id, '_menu_item_menu_item_parent', '0' );

	$sql[] = 'DELETE FROM ' . $q( 'postmeta' ) . ' WHERE `post_id` = ' . $target_id_expr . ' AND `meta_key` IN (' . $menu_meta_list . ');';

	foreach ( $menu_meta_keys as $meta_key ) {
		if ( empty( $meta[ $local_id ][ $meta_key ] ) ) {
			continue;
		}

		foreach ( $meta[ $local_id ][ $meta_key ] as $meta_value ) {
			if ( '_menu_item_object_id' === $meta_key ) {
				if ( 'post_type' === $type && isset( $deployable_ids[ $object_id ] ) ) {
					$value_expr = '(SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . $object_id . ')';
				} else {
					$value_expr = $target_id_expr;
				}
			} elseif ( '_menu_item_menu_item_parent' === $meta_key ) {
				$value_expr = $parent_id > 0
					? 'COALESCE((SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . $parent_id . '), 0)'
					: '0';
			} else {
				$value_expr = fisica_sql_text( fisica_normalize_value( $meta_value, $source_url, $target_url ) );
			}

			$sql[] = 'INSERT INTO ' . $q( 'postmeta' ) . ' (`post_id`, `meta_key`, `meta_value`) VALUES (' . $target_id_expr . ', ' . fisica_sql_text( $meta_key ) . ', ' . $value_expr . ');';
		}
	}

	$target_tt_expr = '(SELECT `target_term_taxonomy_id` FROM `tmp_fisica_deploy_term_map` WHERE `local_term_taxonomy_id` = ' . (int) $relationship['term_taxonomy_id'] . ')';
	$sql[] = 'DELETE tr FROM ' . $q( 'term_relationships' ) . ' tr INNER JOIN ' . $q( 'term_taxonomy' ) . ' tt ON tt.`term_taxonomy_id` = tr.`term_taxonomy_id` AND tt.`taxonomy` = ' . fisica_sql_text( 'nav_menu' ) . ' WHERE tr.`object_id` = ' . $target_id_expr . ';';
	$sql[] = 'INSERT INTO ' . $q( 'term_relationships' ) . ' (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (' . $target_id_expr . ', ' . $target_tt_expr . ', ' . (int) $relationship['term_order'] . ') ON DUPLICATE KEY UPDATE `term_order` = VALUES(`term_order`);';
	$sql[] = '';
}

// Remove the former Ensino wrapper after its children become top-level items.
$sql[] = '-- Remove item de menu obsoleto: Ensino sob O Instituto';
$sql[] = 'SET @fisica_obsolete_ensino_id := (SELECT child.`ID` FROM ' . $q( 'posts' ) . ' child INNER JOIN ' . $q( 'postmeta' ) . ' parent_meta ON parent_meta.`post_id` = child.`ID` AND parent_meta.`meta_key` = ' . fisica_sql_text( '_menu_item_menu_item_parent' ) . ' INNER JOIN ' . $q( 'posts' ) . ' parent ON parent.`ID` = CAST(parent_meta.`meta_value` AS UNSIGNED) INNER JOIN ' . $q( 'term_relationships' ) . ' tr ON tr.`object_id` = child.`ID` INNER JOIN ' . $q( 'term_taxonomy' ) . ' tt ON tt.`term_taxonomy_id` = tr.`term_taxonomy_id` AND tt.`taxonomy` = ' . fisica_sql_text( 'nav_menu' ) . ' INNER JOIN ' . $q( 'terms' ) . ' term ON term.`term_id` = tt.`term_id` AND term.`slug` = ' . fisica_sql_text( 'menu' ) . ' WHERE child.`post_type` = ' . fisica_sql_text( 'nav_menu_item' ) . ' AND child.`post_title` = ' . fisica_sql_text( 'Ensino' ) . ' AND parent.`post_type` = ' . fisica_sql_text( 'nav_menu_item' ) . ' AND parent.`post_title` = ' . fisica_sql_text( 'O Instituto' ) . ' ORDER BY child.`ID` LIMIT 1);';
$sql[] = 'DELETE FROM ' . $q( 'term_relationships' ) . ' WHERE `object_id` = @fisica_obsolete_ensino_id;';
$sql[] = 'DELETE FROM ' . $q( 'postmeta' ) . ' WHERE `post_id` = @fisica_obsolete_ensino_id;';
$sql[] = 'DELETE FROM ' . $q( 'posts' ) . ' WHERE `ID` = @fisica_obsolete_ensino_id AND `post_type` = ' . fisica_sql_text( 'nav_menu_item' ) . ';';
$sql[] = '';

foreach ( $nav_terms as $term ) {
	$target_tt_expr = '(SELECT `target_term_taxonomy_id` FROM `tmp_fisica_deploy_term_map` WHERE `local_term_taxonomy_id` = ' . (int) $term['term_taxonomy_id'] . ')';
	$sql[] = 'UPDATE ' . $q( 'term_taxonomy' ) . ' SET `count` = (SELECT COUNT(*) FROM ' . $q( 'term_relationships' ) . ' WHERE `term_taxonomy_id` = ' . $target_tt_expr . ') WHERE `term_taxonomy_id` = ' . $target_tt_expr . ';';
}
$sql[] = '';

$option_names = array( 'show_on_front', 'page_on_front', 'page_for_posts', 'permalink_structure', 'elementor_active_kit', 'site_icon' );
$options_rows = $wpdb->get_results(
	"SELECT option_name, option_value, autoload FROM {$wpdb->options} WHERE option_name IN ('show_on_front','page_on_front','page_for_posts','permalink_structure','elementor_active_kit','site_icon')",
	ARRAY_A
);

foreach ( $options_rows as $option ) {
	$option_name  = $option['option_name'];
	$option_value = $option['option_value'];

	if ( in_array( $option_name, array( 'page_on_front', 'page_for_posts', 'elementor_active_kit', 'site_icon' ), true ) && (int) $option_value > 0 ) {
		$value_expr = 'COALESCE((SELECT `target_id` FROM `tmp_fisica_deploy_id_map` WHERE `local_id` = ' . (int) $option_value . '), 0)';
	} else {
		$value_expr = fisica_sql_text( fisica_normalize_value( $option_value, $source_url, $target_url ) );
	}

	$sql[] = 'INSERT INTO ' . $q( 'options' ) . ' (`option_name`, `option_value`, `autoload`) VALUES (' . fisica_sql_text( $option_name ) . ', ' . $value_expr . ', ' . fisica_sql_text( $option['autoload'] ) . ') ON DUPLICATE KEY UPDATE `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`);';
}

$sql[] = 'DELETE FROM ' . $q( 'options' ) . ' WHERE `option_name` = ' . fisica_sql_text( 'rewrite_rules' ) . ';';
$sql[] = 'INSERT INTO ' . $q( 'options' ) . ' (`option_name`, `option_value`, `autoload`) VALUES (' . fisica_sql_text( 'fisica_deploy_last_release' ) . ', ' . fisica_sql_text( $release ) . ', ' . fisica_sql_text( 'no' ) . ') ON DUPLICATE KEY UPDATE `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`);';
$sql[] = '';
$sql[] = 'DELETE pm FROM ' . $q( 'postmeta' ) . ' pm INNER JOIN `tmp_fisica_deploy_id_map` m ON m.`target_id` = pm.`post_id` WHERE pm.`meta_key` IN (' . implode( ', ', array_map( 'fisica_sql_text', array( '_elementor_css', '_elementor_element_cache', '_elementor_page_assets' ) ) ) . ');';
$sql[] = '';
$sql[] = 'COMMIT;';
$sql[] = 'SET SQL_SAFE_UPDATES = @fisica_old_sql_safe_updates;';
$sql[] = 'SELECT ' . fisica_sql_text( 'Deploy de conteudo concluido. Regere o CSS/dados do Elementor e limpe o cache.' ) . ' AS `resultado`;';
$sql[] = '';

$output_directory = dirname( $output_path );
if ( ! is_dir( $output_directory ) && ! mkdir( $output_directory, 0775, true ) && ! is_dir( $output_directory ) ) {
	fwrite( STDERR, "Nao foi possivel criar a pasta de saida: {$output_directory}\n" );
	exit( 1 );
}

$bytes = file_put_contents( $output_path, implode( "\r\n", $sql ) );
if ( false === $bytes ) {
	fwrite( STDERR, "Nao foi possivel gravar o SQL: {$output_path}\n" );
	exit( 1 );
}

fwrite( STDOUT, "SQL idempotente criado: {$output_path}\n" );
fwrite( STDOUT, 'Posts/anexos gerenciados: ' . count( $posts ) . "\n" );
fwrite( STDOUT, 'Itens de menu gerenciados: ' . count( $menu_items ) . "\n" );
fwrite( STDOUT, 'Tamanho: ' . number_format( $bytes / 1024 / 1024, 2, ',', '.' ) . " MB\n" );
