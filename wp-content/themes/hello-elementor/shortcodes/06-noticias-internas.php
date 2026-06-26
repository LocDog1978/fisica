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
				// 'lead'       => 'Em 12 de março, foi realizada a Recepção dos Estudantes 2026/1 do Instituto de Física da UERJ. O encontro foi marcado por momentos de inspiração, integração e acolhimento, reunindo calouros, veteranos, docentes, estudantes da pós-graduação e representantes da comunidade acadêmica.',
				'intro'      => '',
				'paragraphs' => [
					'Em 12 de março foi realizada a Recepção dos Estudantes 2026/1 que reuniu calouros, veteranos, docentes, estudantes da pós-graduação e representantes da comunidade acadêmica em uma programação voltada ao acolhimento, à integração e à apresentação da vida universitária. O encontro foi marcado por momentos de inspiração, integração e acolhimento, reunindo calouros, veteranos, docentes, estudantes da pós-graduação e representantes da comunidade acadêmica.',
					'Durante o evento, os participantes puderam conhecer um pouco mais sobre o trabalho científico desenvolvido no Instituto, além de conversar com egressos do curso sobre suas trajetórias acadêmicas e profissionais na área da Física.',
					'Realizada sempre na quinta-feira da primeira semana do período letivo, a recepção é uma construção coletiva do CAFIS, dos estudantes da pós-graduação, da coordenação da graduação, dos departamentos e da Direção do Instituto de Física.',
					'A programação contou com a participação de diferentes estações do Instituto, nas quais foram apresentados projetos, laboratórios e iniciativas que fazem parte do cotidiano acadêmico e científico da unidade.',
					'Nesta edição, o Instituto também recebeu a professora Claudia Miliauskas, da Faculdade de Ciências Médicas da UERJ, que apresentou ações e iniciativas da Universidade voltadas ao cuidado com a saúde mental da comunidade acadêmica.',
					'Agradecemos a todas e todos que participaram da Recepção dos Estudantes 2026/1 e colaboraram para a realização do evento.',
					'Sejam muito bem-vindos e bem-vindas ao Instituto de Física da UERJ.',
				],
			],
			'visita-tecnica-inpe-2026'       => [
				'eyebrow'    => 'Visita técnica',
				'category'   => 'Programa de Enriquecimento Curricular',
				'lead'       => 'Com a participação de três professores e 35 estudantes do Instituto de Física da UERJ, foi realizada a visita técnica ao Instituto Nacional de Pesquisas Espaciais (INPE), em São José dos Campos, SP.',
				'intro'      => 'A atividade reuniu estudantes e docentes do Instituto de Física da UERJ em uma programação de formação acadêmica voltada à aproximação com laboratórios, pesquisas e estruturas estratégicas do Instituto Nacional de Pesquisas Espaciais.',
				'paragraphs' => [
					'Com a participação de três professores e 35 estudantes do Instituto de Física da UERJ, foi realizada a visita técnica ao Instituto Nacional de Pesquisas Espaciais (INPE), em São José dos Campos, SP.',
					'Durante a visita, estudantes e professores conheceram quatro laboratórios do INPE: Tokamak, Ondas Gravitacionais, Controle e Rastreio de Satélites e o Laboratório de Integração e Testes.',
					'A visita proporcionou contato direto com ambientes de pesquisa e infraestrutura científica vinculados a diferentes áreas estratégicas da Física e da tecnologia espacial.',
					'A atividade faz parte do Programa de Enriquecimento Curricular do Instituto de Física da Universidade do Estado do Rio de Janeiro, organizado pela Direção e pelo CAFIS.',
					'O programa foi iniciado em 2024 e prevê a realização de pelo menos uma visita técnica desse tipo a cada período letivo.',
					'Agradecemos a todos e todas que participaram desse evento e também às equipes da PR-1 e da DAF pelo apoio e pela logística, que tornaram possível mais essa atividade do Instituto de Física.',
				],
			],
			'novos-equipamentos-laboratorios-mecanica-2026' => [
				'eyebrow'        => "Infraestrutura",
				'category'       => "Laborat\u{00F3}rios de Mec\u{00E2}nica",
				'lead'           => "Recebemos no dia 19 de fevereiro de 2026 o carregamento dos novos equipamentos para os dois laborat\u{00F3}rios de Mec\u{00E2}nica, respons\u{00E1}veis pelas disciplinas de Mec\u{00E2}nica F\u{00ED}sica do curso de F\u{00ED}sica e de F\u{00ED}sica Experimental I do Ciclo B\u{00E1}sico do CTC.",
				'intro'          => "A chegada dos novos equipamentos representa um avan\u{00E7}o importante para a atualiza\u{00E7}\u{00E3}o tecnol\u{00F3}gica dos laborat\u{00F3}rios de Mec\u{00E2}nica e para o fortalecimento da forma\u{00E7}\u{00E3}o experimental oferecida aos estudantes do Instituto de F\u{00ED}sica da UERJ.",
				'attachment_ids' => [ 1074, 1073, 1072, 1071, 1079 ],
				'paragraphs'     => [
					"Recebemos no dia 19 de fevereiro de 2026 o carregamento dos novos equipamentos para os dois laborat\u{00F3}rios de Mec\u{00E2}nica, respons\u{00E1}veis pelas disciplinas de Mec\u{00E2}nica F\u{00ED}sica do curso de F\u{00ED}sica e de F\u{00ED}sica Experimental I do Ciclo B\u{00E1}sico do CTC.",
					"S\u{00E3}o 16 conjuntos com diversos experimentos de Cinem\u{00E1}tica e Din\u{00E2}mica, temas fundamentais na forma\u{00E7}\u{00E3}o dos nossos estudantes.",
					"A Dire\u{00E7}\u{00E3}o do IF agradece o trabalho conjunto realizado pelos professores e professoras desses laborat\u{00F3}rios e pela chefia do DFT na elabora\u{00E7}\u{00E3}o deste projeto, que atualiza tecnologicamente uma parte fundamental do ensino de F\u{00ED}sica na UERJ.",
					"Agradecemos \u{00E0} Dire\u{00E7}\u{00E3}o do CTC, que esteve conosco \u{00E0} frente deste processo, pelo envolvimento com as reais necessidades do Instituto. Agradecemos \u{00E0} Reitoria, pela parceria e pelo comprometimento com a melhoria do ensino no IF.",
					"Agradecemos tamb\u{00E9}m \u{00E0} deputada estadual Elika Takimoto, que destinou recursos de emenda parlamentar para a aquisi\u{00E7}\u{00E3}o dos equipamentos, por acreditar no impacto positivo que essa iniciativa pode gerar para a melhoria da forma\u{00E7}\u{00E3}o dos estudantes da UERJ e, consequentemente, da sociedade.",
					"Um agradecimento especial \u{00E0} DAF pelo trabalho na tramita\u{00E7}\u{00E3}o do processo e, em especial, \u{00E0} dedicada equipe da Divis\u{00E3}o de Importa\u{00E7}\u{00E3}o da UERJ, pelo enorme esfor\u{00E7}o em resolver in\u{00FA}meras situa\u{00E7}\u{00F5}es internas e externas, tornando poss\u{00ED}vel a concretiza\u{00E7}\u{00E3}o deste dia.",
					"Seguimos avan\u{00E7}ando, e em breve outros laborat\u{00F3}rios tamb\u{00E9}m ser\u{00E3}o atualizados, fortalecendo ainda mais a qualidade do ensino e da forma\u{00E7}\u{00E3}o no Instituto de F\u{00ED}sica.",
				],
			],
			'formatura-da-turma-de-2025-2'  => [
				'eyebrow'        => 'Formatura',
				'category'       => 'Turma 2025/2',
				'lead'           => "Depois de v\u{00E1}rios anos, o Instituto de F\u{00ED}sica da UERJ voltou a realizar uma festa de formatura de Licenciatura e Bacharelado, celebrando a conclus\u{00E3}o de 20 estudantes da turma de 2025/2.",
				'intro'          => "A celebra\u{00E7}\u{00E3}o da turma de 2025/2 marcou o retorno da festa de formatura de Licenciatura e Bacharelado do Instituto de F\u{00ED}sica da UERJ, reunindo estudantes, docentes, t\u{00E9}cnicos e a Dire\u{00E7}\u{00E3}o em um momento de reconhecimento, acolhimento e comemora\u{00E7}\u{00E3}o institucional.",
				'attachment_ids' => [ 1068, 1067, 1066, 1065, 1064, 1063, 1062, 1061, 1060, 1059 ],
				'paragraphs'     => [
					"Depois de v\u{00E1}rios anos, no dia 13 de abril, tivemos a alegria de voltar a realizar uma festa de formatura de Licenciatura e Bacharelado, desta vez com 20 estudantes do Instituto de F\u{00ED}sica.",
					"Na F\u{00ED}sica, 20 formandos \u{00E9} uma grata satisfa\u{00E7}\u{00E3}o.",
					"Parab\u{00E9}ns \u{00E0} turma de 2025/2, n\u{00E3}o s\u{00F3} pela grande quantidade de formandos, mas pelo esfor\u{00E7}o, dedica\u{00E7}\u{00E3}o e desempenho no curso.",
					"A Dire\u{00E7}\u{00E3}o do Instituto de F\u{00ED}sica parabeniza os estudantes e todos os professores e t\u{00E9}cnicos pela linda celebra\u{00E7}\u{00E3}o de hoje. Ela nos impulsiona a, cada vez mais, transpor os obst\u{00E1}culos para reformar nossos cursos, equipar nossos laborat\u{00F3}rios, impulsionar as coopera\u{00E7}\u{00F5}es nacionais e internacionais, apoiar a pesquisa b\u{00E1}sica e a aplicada, incentivar os projetos de extens\u{00E3}o e acolher estudantes, t\u{00E9}cnicos e professores.",
					"Viva a turma de 2025/2! Viva o Instituto de F\u{00ED}sica da UERJ!",
				],
			],
			'rpc-2026-na-uerj-abre-inscricoes-e-submissao-de-resumos' => [
				'eyebrow'        => 'Evento internacional',
				'category'       => 'RPC 2026',
				'lead'           => 'UERJ sediará pela primeira vez no Brasil e na América do Sul a International Conference on Resistive Plate Chambers and Related Detectors (RPC 2026), reunindo pesquisadores e especialistas de diversos países.',
				'intro'          => 'Estão abertas as inscrições e a submissão de resumos para a XVIII International Conference on Resistive Plate Chambers and Related Detectors (RPC 2026), que será realizada de 14 a 18 de setembro de 2026, na Universidade do Estado do Rio de Janeiro (UERJ).',
				'attachment_ids' => [ 1100 ],
				'links'          => [
					[
						'label' => 'Site oficial',
						'url'   => 'https://rpc2026.uerj.br/',
					],
					[
						'label' => 'Página do evento',
						'url'   => 'https://indico.global/event/1534',
					],
				],
				'paragraphs'     => [
					'Estão abertas as inscrições e a submissão de resumos para a XVIII International Conference on Resistive Plate Chambers and Related Detectors (RPC 2026), que será realizada de 14 a 18 de setembro de 2026, na Universidade do Estado do Rio de Janeiro (UERJ).',
					'A conferência reunirá pesquisadores, estudantes e especialistas de diferentes países para discutir avanços no desenvolvimento e nas aplicações de detectores de partículas, com destaque para instrumentação científica voltada à física de altas energias, astropartículas, imageamento e outras áreas tecnológicas.',
					'A realização da RPC 2026 na UERJ marca a primeira vez que esse tradicional evento internacional será sediado no Brasil e na América do Sul, consolidando a Universidade e o Instituto de Física Armando Dias Tavares como espaço de produção científica de excelência e de articulação com redes internacionais de pesquisa.',
					'Ao receber pesquisadores, estudantes e especialistas de diferentes países, a UERJ amplia sua projeção institucional, fortalece sua inserção no cenário científico global e reafirma seu papel estratégico na promoção da ciência, da inovação e da cooperação acadêmica.',
					'Ao mesmo tempo, a infraestrutura, a tradição e a diversidade acadêmica da Universidade oferecem ao evento um ambiente particularmente qualificado, capaz de enriquecer as discussões e ampliar seu impacto científico e institucional.',
					'A submissão de resumos para apresentações orais e pôsteres está aberta até 24 de junho de 2026.',
				],
			],
			'instituto-de-fisica-recebe-novos-equipamentos-fisica-moderna' => [
				'eyebrow'        => 'Infraestrutura',
				'category'       => "Laboratório Didático de Física Moderna",
				'lead'           => "Aquisição fortalece o ensino experimental na graduação e amplia as possibilidades de formação científica de estudantes de bacharelado e licenciatura em Física.",
				'intro'          => "O Instituto de Física recebeu novos equipamentos destinados a um dos laboratórios didáticos da graduação, com foco nos experimentos de Física Moderna. A aquisição representa um importante avanço para o ensino experimental, permitindo que estudantes tenham contato prático com fenômenos fundamentais da Física contemporânea.",
				'attachment_ids' => [ 1199, 1198, 1197 ],
				'paragraphs'     => [
					"Entre os equipamentos recebidos estão uma unidade de raios X com controle digital, sistemas para experimentos de ESR/NMR — Ressonância do Spin do Elétron e Ressonância Magnética Nuclear — e um conjunto completo para o estudo do efeito Zeeman, composto por eletroímã e interferômetro de Fabry-Perot.",
					"Com os novos equipamentos da PHYWE, os estudantes poderão investigar conceitos como estrutura atômica, níveis de energia, spin do elétron, magnetismo, espectroscopia, raios X e fundamentos da mecânica quântica, aproximando a formação acadêmica da prática científica realizada em laboratórios de pesquisa.",
					"A iniciativa terá impacto direto na formação de estudantes dos cursos de bacharelado e licenciatura em Física, contribuindo para o fortalecimento do ensino experimental, da formação científica e da preparação de futuros físicos e professores de Física.",
					"O Instituto de Física agradece à Reitoria da Universidade pelo apoio à aquisição, à Direção do CTC, à Diretoria de Administração Financeira (DAF) e, especialmente, à equipe da Divisão de Importação, cuja competência e empenho foram fundamentais para a operacionalização do processo.",
					"O Instituto também registra seu agradecimento à coordenação do laboratório e à equipe do Departamento de Física Nuclear e Altas Energias, que participaram do planejamento e acompanharam cuidadosamente as etapas necessárias para a chegada dos equipamentos.",
					"A chegada dos novos equipamentos marca um momento significativo para o Instituto de Física, reforçando o compromisso com a qualidade do ensino, a valorização da prática experimental e a formação de profissionais preparados para atuar na ciência, na educação e na pesquisa.",
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

if ( ! function_exists( 'fisica_get_internal_news_gallery_attachments_for_article' ) ) {
	/**
	 * Resolve gallery attachments for an article.
	 *
	 * @param array<string, mixed> $article Article configuration.
	 *
	 * @return int[]
	 */
	function fisica_get_internal_news_gallery_attachments_for_article( $article ) {
		if ( ! empty( $article['attachment_ids'] ) && is_array( $article['attachment_ids'] ) ) {
			return array_values(
				array_filter(
					array_map( 'intval', $article['attachment_ids'] ),
					static function ( $attachment_id ) {
						return $attachment_id > 0;
					}
				)
			);
		}

		$limit = ! empty( $article['gallery_limit'] ) ? (int) $article['gallery_limit'] : 5;

		return array_values( fisica_get_latest_news_gallery_attachments( $limit ) );
	}
}

if ( ! function_exists( 'fisica_render_internal_news_gallery_figure' ) ) {
	/**
	 * Render a single responsive figure for an internal news article.
	 *
	 * @param int    $attachment_id Attachment id.
	 * @param string $variant       Variant class for layout treatment.
	 * @param string $alt           Contextual alternative text.
	 *
	 * @return string
	 */
	function fisica_render_internal_news_gallery_figure( $attachment_id, $variant = 'default', $alt = '' ) {
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
				'alt'           => $alt,
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

		$figure_class = 'fisica-news-article__figure';

		if ( 'default' !== $variant ) {
			$figure_class .= ' is-' . sanitize_html_class( $variant );
		}

		$markup  = '<figure class="' . esc_attr( $figure_class ) . '">';
		$markup .= '<a href="' . esc_url( wp_get_attachment_url( $attachment_id ) ) . '" class="fisica-news-article__gallery-link">';
		$markup .= $image_html;
		$markup .= '</a>';
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
		$attachment_ids = fisica_get_internal_news_gallery_attachments_for_article( $article );

		if ( 'recepcao-dos-estudantes-2026-1' === $slug ) {
			$attachment_ids = [ 1044, 1043, 1042, 1041, 1040 ];
		}

		if ( 'visita-tecnica-inpe-2026' === $slug ) {
			$attachment_ids = [ 1053, 1052, 1051, 1050, 1049 ];
		}

		$published_date = get_the_date( 'j \d\e F \d\e Y', $post );
		$lead           = ! empty( $article['lead'] ) ? $article['lead'] : $article['intro'];
		$copy           = fisica_group_internal_news_paragraphs( $article['paragraphs'] );
		$image_alt      = 'Registro fotográfico: ' . get_the_title( $post );
		$figures        = [
			0 => isset( $attachment_ids[0] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[0], 'featured', $image_alt ) : '',
			1 => isset( $attachment_ids[1] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[1], 'editorial', $image_alt ) : '',
			2 => isset( $attachment_ids[2] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[2], 'editorial', $image_alt ) : '',
			3 => isset( $attachment_ids[3] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[3], 'editorial', $image_alt ) : '',
			4 => isset( $attachment_ids[4] ) ? fisica_render_internal_news_gallery_figure( $attachment_ids[4], 'compact', $image_alt ) : '',
		];
		$gallery_extra  = [];

		if ( count( $attachment_ids ) > 5 ) {
			foreach ( array_slice( $attachment_ids, 5 ) as $attachment_id ) {
				$gallery_extra[] = fisica_render_internal_news_gallery_figure( $attachment_id, 'gallery', $image_alt );
			}
		}

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

							<?php if ( ! empty( $gallery_extra ) ) : ?>
								<div class="fisica-news-article__gallery-grid" aria-label="Galeria da noticia">
									<?php foreach ( $gallery_extra as $gallery_item ) : ?>
										<?php echo wp_kses_post( $gallery_item ); ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $article['links'] ) && is_array( $article['links'] ) ) : ?>
								<div class="fisica-news-article__text-stack fisica-news-article__text-stack--links">
									<?php foreach ( $article['links'] as $link_item ) : ?>
										<?php
										$link_label = isset( $link_item['label'] ) ? trim( (string) $link_item['label'] ) : '';
										$link_url   = isset( $link_item['url'] ) ? trim( (string) $link_item['url'] ) : '';

										if ( '' === $link_label || '' === $link_url ) {
											continue;
										}
										?>
										<p><strong><?php echo esc_html( $link_label ); ?>:</strong> <a href="<?php echo esc_url( $link_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $link_url ); ?></a></p>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

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
