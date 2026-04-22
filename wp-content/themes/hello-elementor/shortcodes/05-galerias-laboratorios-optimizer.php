<?php
/**
 * Optimiza a entrega das galerias dos laboratorios com imagens responsivas.
 */

if ( ! function_exists( 'fisica_get_lab_gallery_slugs' ) ) {
	/**
	 * Return the slugs for all lab gallery pages.
	 *
	 * @return string[]
	 */
	function fisica_get_lab_gallery_slugs() {
		return [
			'fotos-lef',
			'fotos-lfpn',
			'fotos-lfm',
			'fotos-lfmedicas',
			'fotos-hepgrid',
			'fotos-lieta',
			'fotos-lfe',
		];
	}
}

if ( ! function_exists( 'fisica_is_lab_gallery_page' ) ) {
	/**
	 * Check if current request is for one of the lab gallery pages.
	 *
	 * @return bool
	 */
	function fisica_is_lab_gallery_page() {
		if ( is_admin() || ! is_singular( 'page' ) ) {
			return false;
		}

		$page = get_queried_object();

		return $page instanceof WP_Post
			&& 'page' === $page->post_type
			&& in_array( $page->post_name, fisica_get_lab_gallery_slugs(), true );
	}
}

if ( ! function_exists( 'fisica_get_lab_gallery_image_sizes' ) ) {
	/**
	 * Return the responsive sizes attribute for gallery grid images.
	 *
	 * @return string
	 */
	function fisica_get_lab_gallery_image_sizes() {
		return '(max-width: 767px) 100vw, (max-width: 1100px) 50vw, 33vw';
	}
}

if ( ! function_exists( 'fisica_get_gallery_image_variants' ) ) {
	/**
	 * Build responsive sources for a gallery image based on pre-generated theme assets.
	 *
	 * @param string $original_url Original uploads URL stored in the page content.
	 *
	 * @return array<string, mixed>|null
	 */
	function fisica_get_gallery_image_variants( $original_url ) {
		static $cache = [];

		$original_url = html_entity_decode( (string) $original_url, ENT_QUOTES, 'UTF-8' );

		if ( isset( $cache[ $original_url ] ) ) {
			return $cache[ $original_url ];
		}

		$uploads_base_url = content_url( 'uploads/' );

		if ( 0 !== strpos( $original_url, $uploads_base_url ) ) {
			$cache[ $original_url ] = null;

			return null;
		}

		$relative = ltrim( str_replace( $uploads_base_url, '', $original_url ), '/' );
		$parts    = explode( '/', $relative );

		if ( count( $parts ) < 3 ) {
			$cache[ $original_url ] = null;

			return null;
		}

		$year      = sanitize_file_name( $parts[0] );
		$month     = sanitize_file_name( $parts[1] );
		$basename  = pathinfo( end( $parts ), PATHINFO_FILENAME );
		$prefix    = $year . '-' . $month . '-' . sanitize_file_name( $basename );
		$asset_dir = get_stylesheet_directory() . '/assets/images/gallery/';
		$asset_url = get_stylesheet_directory_uri() . '/assets/images/gallery/';

		$variants = [
			'thumb'  => $prefix . '-thumb.jpg',
			'medium' => $prefix . '-medium.jpg',
			'large'  => $prefix . '-large.jpg',
		];

		$paths = [];
		foreach ( $variants as $key => $filename ) {
			$paths[ $key ] = [
				'path' => $asset_dir . $filename,
				'url'  => $asset_url . $filename,
			];
		}

		if ( ! file_exists( $paths['medium']['path'] ) ) {
			$cache[ $original_url ] = null;

			return null;
		}

		$medium_size = getimagesize( $paths['medium']['path'] );

		if ( false === $medium_size ) {
			$cache[ $original_url ] = null;

			return null;
		}

		$large_url = file_exists( $paths['large']['path'] ) ? $paths['large']['url'] : $original_url;
		$thumb_url = file_exists( $paths['thumb']['path'] ) ? $paths['thumb']['url'] : $paths['medium']['url'];

		$srcset = [];
		if ( file_exists( $paths['thumb']['path'] ) ) {
			$srcset[] = esc_url( $paths['thumb']['url'] ) . ' 480w';
		}
		$srcset[] = esc_url( $paths['medium']['url'] ) . ' 960w';
		if ( file_exists( $paths['large']['path'] ) ) {
			$srcset[] = esc_url( $paths['large']['url'] ) . ' 1600w';
		}

		$cache[ $original_url ] = [
			'thumb_url'  => $thumb_url,
			'medium_url' => $paths['medium']['url'],
			'large_url'  => $large_url,
			'width'      => (int) $medium_size[0],
			'height'     => (int) $medium_size[1],
			'srcset'     => implode( ', ', $srcset ),
			'sizes'      => fisica_get_lab_gallery_image_sizes(),
		];

		return $cache[ $original_url ];
	}
}

if ( ! function_exists( 'fisica_get_lab_gallery_attachment_id' ) ) {
	/**
	 * Resolve an attachment id from a gallery image URL.
	 *
	 * @param string $image_url Public image URL.
	 *
	 * @return int
	 */
	function fisica_get_lab_gallery_attachment_id( $image_url ) {
		static $cache = [];

		$image_url = html_entity_decode( (string) $image_url, ENT_QUOTES, 'UTF-8' );

		if ( isset( $cache[ $image_url ] ) ) {
			return $cache[ $image_url ];
		}

		$attachment_id = attachment_url_to_postid( $image_url );

		if ( ! $attachment_id ) {
			$normalized_url = fisica_normalize_local_attachment_url_scheme( $image_url );

			if ( $normalized_url !== $image_url ) {
				$attachment_id = attachment_url_to_postid( $normalized_url );
			}
		}

		$cache[ $image_url ] = (int) $attachment_id;

		return $cache[ $image_url ];
	}
}

if ( ! function_exists( 'fisica_ensure_lab_gallery_attachment_sizes' ) ) {
	/**
	 * Generate missing WordPress sub-sizes for a gallery attachment when needed.
	 *
	 * @param int $attachment_id Attachment id.
	 *
	 * @return array<string, mixed>
	 */
	function fisica_ensure_lab_gallery_attachment_sizes( $attachment_id ) {
		static $cache = [];

		$attachment_id = (int) $attachment_id;

		if ( isset( $cache[ $attachment_id ] ) ) {
			return $cache[ $attachment_id ];
		}

		$metadata = wp_get_attachment_metadata( $attachment_id );

		if ( ! is_array( $metadata ) ) {
			$cache[ $attachment_id ] = [];

			return $cache[ $attachment_id ];
		}

		$sizes = isset( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ? $metadata['sizes'] : [];

		if ( empty( $sizes ) && wp_attachment_is_image( $attachment_id ) ) {
			if ( function_exists( 'wp_update_image_subsizes' ) ) {
				wp_update_image_subsizes( $attachment_id );
			}

			clean_attachment_cache( $attachment_id );
			$metadata = wp_get_attachment_metadata( $attachment_id );
		}

		$cache[ $attachment_id ] = is_array( $metadata ) ? $metadata : [];

		return $cache[ $attachment_id ];
	}
}

if ( ! function_exists( 'fisica_get_lab_gallery_image_markup' ) ) {
	/**
	 * Build optimized image markup for the grid while preserving a larger image for the anchor.
	 *
	 * @param string $original_url  Original image URL.
	 * @param string $alt           Image alt text.
	 *
	 * @return array<string, string>|null
	 */
	function fisica_get_lab_gallery_image_markup( $original_url, $alt ) {
		$original_url = html_entity_decode( (string) $original_url, ENT_QUOTES, 'UTF-8' );
		$alt          = trim( (string) $alt );
		$variants     = fisica_get_gallery_image_variants( $original_url );

		if ( $variants ) {
			return [
				'lightbox_url' => (string) $variants['large_url'],
				'image_html'   => sprintf(
					'<img src="%1$s" srcset="%2$s" sizes="%3$s" loading="lazy" decoding="async" alt="%4$s" width="%5$d" height="%6$d">',
					esc_url( $variants['medium_url'] ),
					esc_attr( $variants['srcset'] ),
					esc_attr( $variants['sizes'] ),
					esc_attr( $alt ),
					(int) $variants['width'],
					(int) $variants['height']
				),
			];
		}

		$attachment_id = fisica_get_lab_gallery_attachment_id( $original_url );

		if ( ! $attachment_id ) {
			return null;
		}

		fisica_ensure_lab_gallery_attachment_sizes( $attachment_id );

		$lightbox_url = wp_get_attachment_image_url( $attachment_id, '2048x2048' );

		if ( ! $lightbox_url ) {
			$lightbox_url = wp_get_attachment_url( $attachment_id );
		}

		if ( ! $alt ) {
			$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		}

		if ( ! $alt ) {
			$alt = get_the_title( $attachment_id );
		}

		$image_html = wp_get_attachment_image(
			$attachment_id,
			'large',
			false,
			[
				'class'    => '',
				'alt'      => $alt,
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => fisica_get_lab_gallery_image_sizes(),
			]
		);

		if ( ! $image_html ) {
			return null;
		}

		return [
			'lightbox_url' => (string) $lightbox_url,
			'image_html'   => $image_html,
		];
	}
}

if ( ! function_exists( 'fisica_optimize_lab_gallery_content' ) ) {
	/**
	 * Replace original gallery image sources with responsive WordPress attachments.
	 *
	 * @param string $content Page content.
	 *
	 * @return string
	 */
	function fisica_optimize_lab_gallery_content( $content ) {
		if ( ! fisica_is_lab_gallery_page() || false === strpos( $content, 'fisica-gallery-grid' ) ) {
			return $content;
		}

		if ( ! class_exists( 'DOMDocument' ) ) {
			return $content;
		}

		$libxml_previous = libxml_use_internal_errors( true );
		$document        = new DOMDocument( '1.0', 'UTF-8' );
		$wrapped_content = '<!DOCTYPE html><html><body><div id="fisica-gallery-root">' . $content . '</div></body></html>';

		if ( ! $document->loadHTML( mb_convert_encoding( $wrapped_content, 'HTML-ENTITIES', 'UTF-8' ) ) ) {
			libxml_clear_errors();
			libxml_use_internal_errors( $libxml_previous );

			return $content;
		}

		$xpath  = new DOMXPath( $document );
		$anchors = $xpath->query(
			'//div[contains(concat(" ", normalize-space(@class), " "), " fisica-gallery-grid ")]' .
			'//a[contains(concat(" ", normalize-space(@class), " "), " fisica-gallery-item ")]'
		);

		if ( ! $anchors instanceof DOMNodeList || 0 === $anchors->length ) {
			libxml_clear_errors();
			libxml_use_internal_errors( $libxml_previous );

			return $content;
		}

		foreach ( $anchors as $anchor ) {
			if ( ! $anchor instanceof DOMElement ) {
				continue;
			}

			$current_image = null;

			foreach ( $anchor->childNodes as $child ) {
				if ( $child instanceof DOMElement && 'img' === strtolower( $child->tagName ) ) {
					$current_image = $child;
					break;
				}
			}

			if ( ! $current_image ) {
				continue;
			}

			$original_url   = $anchor->getAttribute( 'href' );
			$fallback_src   = $current_image->getAttribute( 'src' );
			$optimized_data = fisica_get_lab_gallery_image_markup(
				$original_url ? $original_url : $fallback_src,
				$current_image->getAttribute( 'alt' )
			);

			if ( ! $optimized_data ) {
				$current_image->setAttribute( 'loading', 'lazy' );
				$current_image->setAttribute( 'decoding', 'async' );
				continue;
			}

			$anchor->setAttribute( 'href', esc_url( $optimized_data['lightbox_url'] ) );

			$image_document = new DOMDocument( '1.0', 'UTF-8' );
			$image_markup   = '<!DOCTYPE html><html><body>' . $optimized_data['image_html'] . '</body></html>';

			if ( ! $image_document->loadHTML( mb_convert_encoding( $image_markup, 'HTML-ENTITIES', 'UTF-8' ) ) ) {
				continue;
			}

			$image_node = $image_document->getElementsByTagName( 'img' )->item( 0 );

			if ( ! $image_node ) {
				continue;
			}

			while ( $anchor->firstChild ) {
				$anchor->removeChild( $anchor->firstChild );
			}

			$anchor->appendChild( $document->importNode( $image_node, true ) );
		}

		$root = $document->getElementById( 'fisica-gallery-root' );

		if ( ! $root ) {
			libxml_clear_errors();
			libxml_use_internal_errors( $libxml_previous );

			return $content;
		}

		$output = '';
		foreach ( $root->childNodes as $child ) {
			$output .= $document->saveHTML( $child );
		}

		libxml_clear_errors();
		libxml_use_internal_errors( $libxml_previous );

		return $output;
	}
}
add_filter( 'the_content', 'fisica_optimize_lab_gallery_content', 30 );
