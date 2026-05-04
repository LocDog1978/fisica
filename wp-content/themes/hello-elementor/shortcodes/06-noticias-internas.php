<?php
/**
 * Internal news article helpers and shortcode.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'fisica_get_internal_news_articles' ) ) {
	/**
	 * Return supported internal news articles keyed by slug.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	function fisica_get_internal_news_articles() {
		return [
			'recepcao-dos-estudantes-2026-1' => [
				'eyebrow'    => 'Vida acadêmica',
				'category'   => 'Recepção 2026/1',
				'lead'       => 'Nesta semana, foi realizada a Recepção dos Estudantes 2026/1 do Instituto de Física da UERJ. O encontro foi marcado por momentos de inspiração, integração e acolhimento, reunindo calouros, veteranos, docentes, estudantes da pós-graduação e representantes da comunidade acadêmica.',
				'intro'      => 'Recepção dos Estudantes 2026/1 reuniu calouros, veteranos, docentes, estudantes da pós-graduação e representantes da comunidade acadêmica em uma programação voltada ao acolhimento, à integração e à apresentação da vida universitária.',
				'paragraphs' => [
					'Nesta semana, foi realizada a Recepção dos Estudantes 2026/1 do Instituto de Física da UERJ. O encontro foi marcado por momentos de inspiração, integração e acolhimento, reunindo calouros, veteranos, docentes, estudantes da pós-graduação e representantes da comunidade acadêmica.',
					'Durante o evento, os participantes puderam conhecer um pouco mais sobre o trabalho científico desenvolvido no Instituto, além de conversar com egressos do curso sobre suas trajetórias acadêmicas e profissionais na área da Física.',
					'Realizada sempre na quinta-feira da primeira semana do período letivo, a recepção é uma construção coletiva do CAFIS, dos estudantes da pós-graduação, da coordenação da graduação, dos departamentos e da Direção do Instituto de Física.',
					'A programação contou com a participação de diferentes estações do Instituto, nas quais foram apresentados projetos, laboratórios e iniciativas que fazem parte do cotidiano acadêmico e científico da unidade.',
					'Nesta edição, o Instituto também recebeu a professora Claudia Miliauskas, da Faculdade de Ciências Médicas da UERJ, que apresentou ações e iniciativas da Universidade voltadas ao cuidado com a saúde mental da comunidade acadêmica.',
					'Agradecemos a todas e todos que participaram da Recepção dos Estudantes 2026/1 e colaboraram para a realização do evento.',
					'Sejam muito bem-vindos e bem-vindas ao Instituto de Física da UERJ!',
				],
			],
			'visita-tecnica-inpe-2026'       => [
				'eyebrow'    => 'Visita técnica',
				'category'   => 'Programa de Enriquecimento Curricular',
				'lead'       => 'Com a participação de três professores e 35 estudantes do Instituto de Física da UERJ, foi realizada a Visita Técnica ao Instituto Nacional de Pesquisas Espaciais (INPE), em São José dos Campos, SP.',
				'intro'      => 'A atividade reuniu estudantes e docentes do Instituto de Física da UERJ em uma programação de formação acadêmica voltada à aproximação com laboratórios, pesquisas e estruturas estratégicas do Instituto Nacional de Pesquisas Espaciais.',
				'paragraphs' => [
					'Com a participação de três professores e 35 estudantes do Instituto de Física da UERJ, foi realizada a Visita Técnica ao Instituto Nacional de Pesquisas Espaciais (INPE), em São José dos Campos, SP.',
					'Durante a visita, estudantes e professores conheceram quatro laboratórios do INPE: Tokamak, Ondas Gravitacionais, Controle e Rastreio de Satélites e o Laboratório de Integração e Testes.',
					'A visita proporcionou contato direto com ambientes de pesquisa e infraestrutura científica vinculados a diferentes áreas estratégicas da Física e da tecnologia espacial.',
					'A atividade faz parte do Programa de Enriquecimento Curricular do Instituto de Física da Universidade do Estado do Rio de Janeiro, organizado pela Direção e pelo CAFIS.',
					'O programa foi iniciado em 2024 e prevê a realização de pelo menos uma visita técnica desse tipo a cada período letivo.',
					'Agradecemos a todos e todas que participaram desse evento e, também, às equipes da PR-1 e da DAF pelo apoio e pela logística que tornaram possível mais essa atividade do Instituto de Física.',
				],
			],
		];
	}
}

if ( ! function_exists( 'fisica_get_latest_news_gallery_attachments' ) ) {
	/**
	 * Get the latest image attachments for internal news galleries.
	 *
	 * @param int $limit Number of images to fetch.
	 *
	 * @return int[]
	 */
	function fisica_get_latest_news_gallery_attachments( $limit = 5 ) {
		$limit = max( 1, (int) $limit );

		return get_posts(
			[
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'post_status'    => 'inherit',
				'posts_per_page' => $limit,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
			]
		);
	}
}

if ( ! function_exists( 'fisica_render_internal_news_gallery_figure' ) ) {
	/**
	 * Render a single responsive figure for an internal news article.
	 *
	 * @param int    $attachment_id Attachment id.
	 * @param string $variant       Variant class for layout treatment.
	 *
	 * @return string
	 */
	function fisica_render_internal_news_gallery_figure( $attachment_id, $variant = 'default' ) {
		$attachment_id = (int) $attachment_id;

		if ( $attachment_id <= 0 ) {
			return '';
		}

		$is_featured = 'featured' === $variant;
		$image_html  = wp_get_attachment_image(
			$attachment_id,
			$is_featured ? 'large' : 'medium_large',
			false,
			[
				'class'         => 'fisica-news-article__image',
				'loading'       => $is_featured ? 'eager' : 'lazy',
				'fetchpriority' => $is_featured ? 'high' : 'auto',
				'decoding'      => 'async',
				'sizes'         => $is_featured
					? '(max-width: 767px) 100vw, (max-width: 1024px) 100vw, 920px'
					: '(max-width: 767px) 100vw, (max-width: 1024px) 100vw, 760px',
			]
		);

		if ( ! $image_html ) {
			return '';
		}

		$caption      = trim( wp_get_attachment_caption( $attachment_id ) );
		$image_title  = get_the_title( $attachment_id );
		$figure_class = 'fisica-news-article__figure';

		if ( 'default' !== $variant ) {
			$figure_class .= ' is-' . sanitize_html_class( $variant );
		}

		$markup  = '<figure class="' . esc_attr( $figure_class ) . '">';
		$markup .= '<a href="' . esc_url( wp_get_attachment_url( $attachment_id ) ) . '" class="fisica-news-article__gallery-link">';
		$markup .= $image_html;
		$markup .= '</a>';

		if ( $caption || $image_title ) {
			$markup .= '<figcaption class="fisica-news-article__gallery-caption">';
			$markup .= esc_html( $caption ? $caption : $image_title );
			$markup .= '</figcaption>';
		}

		$markup .= '</figure>';

		return $markup;
	}
}

if ( ! function_exists( 'fisica_group_internal_news_paragraphs' ) ) {
	/**
	 * Group paragraphs into editorial slots used by the internal news layout.
	 *
	 * @param string[] $paragraphs Normalized paragraph list.
	 *
	 * @return array<string, mixed>
	 */
	function fisica_group_internal_news_paragraphs( $paragraphs ) {
		$paragraphs = array_values(
			array_filter(
				array_map( 'trim', (array) $paragraphs ),
				static function ( $paragraph ) {
					return '' !== $paragraph;
				}
			)
		);

		$opening   = $paragraphs[0] ?? '';
		$closing   = count( $paragraphs ) > 1 ? array_pop( $paragraphs ) : '';
		$remaining = array_slice( $paragraphs, 1 );
		$chunks    = array_chunk( $remaining, 2 );

		return [
			'opening' => $opening,
			'closing' => $closing,
			'blocks'  => [
				$chunks[0] ?? [],
				$chunks[1] ?? [],
				$chunks[2] ?? [],
			],
			'tail'    => $chunks[3] ?? [],
		];
	}
}

if ( ! function_exists( 'shortcode_fisica_noticia_interna' ) ) {
	/**
	 * Render an internal news article page.
	 *
	 * @param array<string, mixed> $atts Shortcode attributes.
	 *
	 * @return string
	 */
	function shortcode_fisica_noticia_interna( $atts ) {
		$atts = shortcode_atts(
			[
				'slug' => '',
			],
			$atts,
			'fisica_noticia_interna'
		);

		$post = get_post();

		if ( ! $post instanceof WP_Post ) {
			return '';
		}

		$slug     = sanitize_title( $atts['slug'] ? $atts['slug'] : $post->post_name );
		$articles = fisica_get_internal_news_articles();

		if ( empty( $articles[ $slug ] ) ) {
			return '';
		}

		$article        = $articles[ $slug ];
		$attachment_ids = array_values( fisica_get_latest_news_gallery_attachments( 5 ) );
		$published_date = get_the_date( 'j \d\e F \d\e Y', $post );
		$lead           = ! empty( $article['lead'] ) ? $article['lead'] : $article['intro'];
		$copy           = fisica_group_internal_news_paragraphs( $article['paragraphs'] );
		$figures        = [
			0 => isset( $attachment_ids[0] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[0], 'featured' ) : '',
			1 => isset( $attachment_ids[1] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[1], 'editorial' ) : '',
			2 => isset( $attachment_ids[2] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[2], 'editorial' ) : '',
			3 => isset( $attachment_ids[3] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[3], 'editorial' ) : '',
			4 => isset( $attachment_ids[4] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[4], 'compact' ) : '',
		];

		ob_start();
		?>
		<section class="fisica-news-article">
			<div class="fisica-news-article__hero">
				<div class="fisica-news-article__hero-card">
					<span class="fisica-news-article__eyebrow"><?php echo esc_html( $article['eyebrow'] ); ?></span>
					<h1 class="fisica-news-article__title"><?php echo esc_html( get_the_title( $post ) ); ?></h1>
					<p class="fisica-news-article__lead"><?php echo esc_html( $lead ); ?></p>
				</div>
			</div>

			<div class="fisica-news-article__wrap">
				<article class="fisica-news-article__main" aria-label="Conteúdo da notícia">
					<div class="fisica-news-article__panel">
						<div class="fisica-news-article__meta">
							<span class="fisica-news-article__meta-item"><?php echo esc_html( $article['category'] ); ?></span>
							<span class="fisica-news-article__meta-item"><?php echo esc_html( $published_date ); ?></span>
						</div>

						<section class="fisica-news-article__section">
							<p class="fisica-news-article__intro"><?php echo esc_html( $article['intro'] ); ?></p>
						</section>

						<section class="fisica-news-article__section fisica-news-article__body">
							<?php if ( $copy['opening'] ) : ?>
								<p><?php echo esc_html( $copy['opening'] ); ?></p>
							<?php endif; ?>

							<?php echo wp_kses_post( $figures[0] ); ?>

							<?php if ( ! empty( $copy['blocks'][0] ) || ! empty( $figures[1] ) ) : ?>
								<div class="fisica-news-article__media-block">
									<?php echo wp_kses_post( $figures[1] ); ?>
									<div class="fisica-news-article__text-stack">
										<?php foreach ( $copy['blocks'][0] as $paragraph ) : ?>
											<p><?php echo esc_html( $paragraph ); ?></p>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $copy['blocks'][1] ) || ! empty( $figures[2] ) ) : ?>
								<div class="fisica-news-article__media-block is-reversed">
									<?php echo wp_kses_post( $figures[2] ); ?>
									<div class="fisica-news-article__text-stack">
										<?php foreach ( $copy['blocks'][1] as $paragraph ) : ?>
											<p><?php echo esc_html( $paragraph ); ?></p>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $copy['blocks'][2] ) || ! empty( $figures[3] ) ) : ?>
								<div class="fisica-news-article__media-block">
									<?php echo wp_kses_post( $figures[3] ); ?>
									<div class="fisica-news-article__text-stack">
										<?php foreach ( $copy['blocks'][2] as $paragraph ) : ?>
											<p><?php echo esc_html( $paragraph ); ?></p>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $copy['tail'] ) ) : ?>
								<div class="fisica-news-article__text-stack fisica-news-article__text-stack--tail">
									<?php foreach ( $copy['tail'] as $paragraph ) : ?>
										<p><?php echo esc_html( $paragraph ); ?></p>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<?php echo wp_kses_post( $figures[4] ); ?>

							<?php if ( $copy['closing'] ) : ?>
								<p><?php echo esc_html( $copy['closing'] ); ?></p>
							<?php endif; ?>
						</section>
					</div>
				</article>
			</div>
		</section>
		<?php

		return (string) ob_get_clean();
	}
}
add_shortcode( 'fisica_noticia_interna', 'shortcode_fisica_noticia_interna' );

if ( ! function_exists( 'fisica_hide_default_title_for_internal_news_pages' ) ) {
	/**
	 * Hide the default theme page title when an internal news shortcode is rendering the hero.
	 *
	 * @param bool $show_title Whether the title should be displayed.
	 *
	 * @return bool
	 */
	function fisica_hide_default_title_for_internal_news_pages( $show_title ) {
		if ( is_admin() || ! is_singular( 'page' ) ) {
			return $show_title;
		}

		$post = get_post();

		if ( ! $post instanceof WP_Post ) {
			return $show_title;
		}

		if ( has_shortcode( $post->post_content, 'fisica_noticia_interna' ) ) {
			return false;
		}

		return $show_title;
	}
}
add_filter( 'hello_elementor_page_title', 'fisica_hide_default_title_for_internal_news_pages', 20 );
