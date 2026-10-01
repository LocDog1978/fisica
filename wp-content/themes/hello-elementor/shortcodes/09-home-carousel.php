<?php
/**
 * Home page news carousel ordered by the publication date of each page.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'fisica_get_home_carousel_items' ) ) {
	/**
	 * Return the pages selected for the home carousel and their presentation data.
	 * The array order is not used for presentation; the page publication date is.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function fisica_get_home_carousel_items() {
		$items = [
			[
				'page_source_id'     => 1454,
				'image_attachment_id' => 1453,
				'label'              => 'Evento internacional',
				'title'              => 'Rio de Janeiro sedia pela primeira vez na América do Sul conferência internacional sobre detectores RPC.',
				'description'        => '',
			],
			[
				'page_source_id' => 1443,
				'image_path'     => '/wp-content/uploads/2026/09/foto_carrossel_desktop_1366x658.jpg',
				'label'          => 'Óptica Aplicada',
				'title'          => 'Óptica Aplicada em destaque no Instituto de Física da UERJ',
				'description'    => '',
			],
			[
				'page_source_id' => 1430,
				'image_path'     => '/wp-content/uploads/2026/09/WhatsApp-Image-2026-09-09-at-14.52.22.jpeg',
				'label'          => 'Ensino de Física',
				'title'          => 'Professora do Instituto de Física da UERJ participa da XXI EPEF, que celebrou 40 anos da pesquisa em Ensino de Física.',
				'description'    => '',
			],
			[
				'page_source_id' => 1411,
				'image_path'     => '/wp-content/uploads/2026/09/IMG_2806.jpg',
				'label'          => 'Extensão',
				'title'          => 'Projeto Rio de Estrelas inicia jornada de divulgação científica pelo interior do Estado',
				'description'    => 'O GalileoMobile Rio de Estrelas leva a Astronomia e a divulgação científica a escolas públicas do interior do Estado do Rio de Janeiro.',
			],
			[
				'page_source_id' => 1378,
				'image_path'     => '/wp-content/uploads/2026/08/20260805_082425.jpg',
				'label'          => 'Evento internacional',
				'title'          => 'Pesquisadores do Instituto de Física da UERJ participam da ICHEP 2026, principal conferência mundial de Física de Altas Energias',
				'description'    => 'Pesquisadores da UERJ que integram o grupo de Física de Altas Energias, juntamente com pesquisadores associados ao grupo da UFRGS, participaram da ICHEP 2026 (International Conference on High Energy Physics), realizada em Natal (RN).',
			],
			[
				'page_source_id' => 1381,
				'image_path'     => '/wp-content/uploads/2026/08/WhatsApp-Image-2026-08-19-at-16.45.45.jpeg',
				'label'          => 'Extensão',
				'title'          => 'Professores do Instituto de Física da UERJ representam a Universidade no II Encontro Nacional de Organizadores de Olimpíadas e Competições de Conhecimento',
				'description'    => 'No dia 13 de agosto de 2026, foi realizado o II Encontro Nacional com Organizadores de Olimpíadas, Torneios, Campeonatos, Feiras, Mostras, Prêmios e Concursos de Conhecimento, promovido pela SEEDUC-RJ, reunindo coordenadores e organizadores de importantes iniciativas voltadas à educação e à promoção do conhecimento.',
			],
			[
				'page_source_id' => 1054,
				'image_path'     => '/wp-content/uploads/2026/03/PHOTO-2026-03-18-16-06-52-enhanced.png',
				'label'          => 'Notícia em destaque',
				'title'          => 'Visita técnica ao Instituto Nacional de Pesquisas Espaciais',
				'description'    => 'Com professores e estudantes do Instituto de Física da UERJ, a atividade reforçou a integração acadêmica, a formação e a aproximação com centros de excelência.',
			],
			[
				'page_source_id' => 1076,
				'image_path'     => '/wp-content/uploads/2026/03/PHOTO-2026-03-18-16-24-39.jpg',
				'label'          => 'Infraestrutura',
				'title'          => 'Novos equipamentos para os laboratórios de Mecânica',
				'description'    => 'Os investimentos ampliam as condições de ensino experimental e fortalecem a formação prática nos cursos atendidos pelo Instituto.',
			],
			[
				'page_source_id' => 1045,
				'image_path'     => '/wp-content/uploads/2026/04/PHOTO-2026-03-18-16-03-28.jpg',
				'label'          => 'Vida acadêmica',
				'title'          => 'Recepção dos Estudantes 2026/1 integra calouros, veteranos e comunidade acadêmica',
				'description'    => 'Programação de acolhimento aproximou estudantes, egressos, laboratórios e iniciativas de cuidado com a saúde mental no início do período letivo.',
			],
			[
				'page_source_id' => 1069,
				'image_path'     => '/wp-content/uploads/2026/05/WhatsApp-Image-2026-05-05-at-15.51.44.jpeg',
				'label'          => 'Vida acadêmica',
				'title'          => 'Instituto de Física celebra formatura da turma de 2025/2',
				'description'    => 'Depois de vários anos, o Instituto de Física da UERJ voltou a realizar uma festa de formatura de Licenciatura e Bacharelado, celebrando a conclusão de 20 estudantes da turma de 2025/2.',
			],
			[
				'page_source_id' => 1204,
				'image_path'     => '/wp-content/uploads/2026/06/image0.jpeg',
				'label'          => 'Infraestrutura',
				'title'          => 'Instituto de Física recebe novos equipamentos para Física Moderna',
				'description'    => 'Aquisição fortalece o ensino experimental e amplia a formação científica dos estudantes de Física.',
			],
			[
				'page_source_id' => 1309,
				'image_path'     => '/wp-content/uploads/2026/07/WhatsApp-Image-2026-07-24-at-12.59.29-1.jpeg',
				'label'          => 'Memória institucional',
				'title'          => 'Instituto de Física celebra lançamento do livro do Professor Alberto Santoro',
				'description'    => 'O Instituto de Física da UERJ celebrou o lançamento de “Memórias de vida”, obra do Professor Alberto Santoro que reúne histórias profissionais, familiares e registros de uma trajetória fundamental para a Física na Universidade.',
			],
		];

		return apply_filters( 'fisica_home_carousel_items', $items );
	}
}

if ( ! function_exists( 'fisica_get_home_carousel_image_url' ) ) {
	/**
	 * Resolve the full image URL of a carousel item.
	 *
	 * @param array<string, mixed> $item Carousel item.
	 *
	 * @return string
	 */
	function fisica_get_home_carousel_image_url( $item ) {
		if ( ! empty( $item['image_attachment_id'] ) ) {
			$attachment_id = fisica_resolve_deployed_post_id( (int) $item['image_attachment_id'], 'attachment' );

			return $attachment_id ? (string) wp_get_attachment_image_url( $attachment_id, 'full' ) : '';
		}

		if ( ! empty( $item['image_path'] ) ) {
			return fisica_site_url( (string) $item['image_path'] );
		}

		return '';
	}
}

if ( ! function_exists( 'fisica_prepare_home_carousel_items' ) ) {
	/**
	 * Resolve pages and order carousel items from newest to oldest publication.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function fisica_prepare_home_carousel_items() {
		$prepared = [];

		foreach ( fisica_get_home_carousel_items() as $source_order => $item ) {
			if ( ! is_array( $item ) || empty( $item['page_source_id'] ) ) {
				continue;
			}

			$page_id = fisica_resolve_deployed_post_id( (int) $item['page_source_id'], 'page' );
			$page    = $page_id ? get_post( $page_id ) : null;
			$image   = fisica_get_home_carousel_image_url( $item );

			if ( ! $page instanceof WP_Post || 'publish' !== $page->post_status || '' === $image ) {
				continue;
			}

			$item['page_id']             = $page_id;
			$item['url']                 = (string) get_permalink( $page_id );
			$item['image_url']           = $image;
			$item['published_timestamp'] = (int) get_post_time( 'U', true, $page );
			$item['published_iso']       = (string) get_post_time( DATE_ATOM, false, $page );
			$item['source_order']        = (int) $source_order;
			$prepared[]                  = $item;
		}

		usort(
			$prepared,
			static function ( $left, $right ) {
				$date_order = $right['published_timestamp'] <=> $left['published_timestamp'];

				return 0 !== $date_order
					? $date_order
					: $left['source_order'] <=> $right['source_order'];
			}
		);

		return $prepared;
	}
}

if ( ! function_exists( 'shortcode_fisica_home_carousel' ) ) {
	/**
	 * Render the existing home carousel with chronological ordering.
	 *
	 * @return string
	 */
	function shortcode_fisica_home_carousel() {
		$items = fisica_prepare_home_carousel_items();

		if ( ! $items ) {
			return '';
		}

		ob_start();
		?>
		<section class="fisica-home-hero">
			<div class="fisica-home-hero__inner">
				<div class="fisica-home-hero__carousel fisica-home-carousel" data-carousel>
					<div class="fisica-home-carousel__track">
						<?php foreach ( $items as $item ) : ?>
							<a class="fisica-home-carousel__slide"
								href="<?php echo esc_url( $item['url'] ); ?>"
								data-published="<?php echo esc_attr( $item['published_iso'] ); ?>"
								style="background-image:url('<?php echo esc_url( $item['image_url'] ); ?>');">
								<div class="fisica-home-carousel__content">
									<span class="fisica-home-carousel__label"><?php echo esc_html( $item['label'] ); ?></span>
									<h3><?php echo esc_html( $item['title'] ); ?></h3>
									<?php if ( ! empty( $item['description'] ) ) : ?>
										<p><?php echo esc_html( $item['description'] ); ?></p>
									<?php endif; ?>
								</div>
							</a>
						<?php endforeach; ?>
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

		  slides.forEach((slide, slideIndex) => {
		    const image = slide.style.backgroundImage || window.getComputedStyle(slide).backgroundImage;
		    slide.style.setProperty('--fisica-carousel-image', image);
		    slide.dataset.carouselMotion = slideIndex % 2 === 0 ? 'right' : 'left';
		  });
		  track.style.transform = '';
		  root.classList.add('is-image-transition-ready');

		  let index = 0;
		  let timer = null;
		  let transitionTimer = null;

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

		  function render(previousIndex = null) {
		    window.clearTimeout(transitionTimer);

		    slides.forEach((slide, slideIndex) => {
		      if (slideIndex !== previousIndex) {
		        slide.classList.remove('is-leaving');
		      }
		      slide.classList.toggle('is-active', slideIndex === index);
		    });

		    if (previousIndex !== null && previousIndex !== index) {
		      const previousSlide = slides[previousIndex];
		      previousSlide.classList.add('is-leaving');
		      transitionTimer = window.setTimeout(() => {
		        previousSlide.classList.remove('is-leaving');
		      }, 900);
		    }

		    dots.forEach((dot, dotIndex) => {
		      dot.classList.toggle('is-active', dotIndex === index);
		    });
		  }

		  function goTo(nextIndex) {
		    const previousIndex = index;
		    index = (nextIndex + slides.length) % slides.length;
		    render(previousIndex);
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
		<?php

		return (string) ob_get_clean();
	}
}
add_shortcode( 'fisica_home_carousel', 'shortcode_fisica_home_carousel' );
