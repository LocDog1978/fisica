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

if ( ! function_exists( 'fisica_get_gallery_image_variants' ) ) {
    /**
     * Build responsive sources for a gallery image based on a public uploads URL.
     *
     * @param string $original_url Original uploads URL stored in the page content.
     *
     * @return array<string, mixed>|null
     */
    function fisica_get_gallery_image_variants( $original_url ) {
        static $cache = [];

        if ( isset( $cache[ $original_url ] ) ) {
            return $cache[ $original_url ];
        }

        $uploads_base_url = content_url( 'uploads/' );
        $uploads_base_dir = WP_CONTENT_DIR . '/uploads/';

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
            'thumb_url' => $thumb_url,
            'medium_url' => $paths['medium']['url'],
            'large_url' => $large_url,
            'width' => (int) $medium_size[0],
            'height' => (int) $medium_size[1],
            'srcset' => implode( ', ', $srcset ),
            'sizes' => '(max-width: 767px) 100vw, (max-width: 1100px) 50vw, 33vw',
            'original_path' => $uploads_base_dir . str_replace( '/', DIRECTORY_SEPARATOR, $relative ),
        ];

        return $cache[ $original_url ];
    }
}

if ( ! function_exists( 'fisica_optimize_lab_gallery_content' ) ) {
    /**
     * Replace original gallery image sources with responsive derivatives.
     *
     * @param string $content Page content.
     *
     * @return string
     */
    function fisica_optimize_lab_gallery_content( $content ) {
        if ( ! fisica_is_lab_gallery_page() || false === strpos( $content, 'fisica-gallery-grid' ) ) {
            return $content;
        }

        $pattern = '#<a\s+class="fisica-gallery-item"\s+href="([^"]+)">\s*<img\s+src="([^"]+)"\s+alt="([^"]*)"\s*>\s*</a>#i';

        return preg_replace_callback(
            $pattern,
            static function ( $matches ) {
                $original_url = html_entity_decode( $matches[1], ENT_QUOTES, 'UTF-8' );
                $alt          = $matches[3];
                $variants     = fisica_get_gallery_image_variants( $original_url );

                if ( null === $variants ) {
                    return $matches[0];
                }

                return sprintf(
                    '<a class="fisica-gallery-item" href="%1$s"><img src="%2$s" srcset="%3$s" sizes="%4$s" loading="lazy" decoding="async" alt="%5$s" width="%6$d" height="%7$d"></a>',
                    esc_url( $variants['large_url'] ),
                    esc_url( $variants['medium_url'] ),
                    esc_attr( $variants['srcset'] ),
                    esc_attr( $variants['sizes'] ),
                    esc_attr( $alt ),
                    (int) $variants['width'],
                    (int) $variants['height']
                );
            },
            $content
        );
    }
}
add_filter( 'the_content', 'fisica_optimize_lab_gallery_content', 30 );
