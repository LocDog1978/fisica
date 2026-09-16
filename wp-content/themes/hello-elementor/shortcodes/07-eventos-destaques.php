<?php
/**
 * Home page events and highlights with automatic expiration.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'fisica_get_featured_events' ) ) {
	/**
	 * Return the events displayed in the "Eventos e Destaques" section.
	 *
	 * A one-day event needs only start_date. For a multi-day event, end_date
	 * contains its last day. Dates use the ISO format so they can be compared
	 * safely in the WordPress timezone.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function fisica_get_featured_events() {
		$events = [
			[
				'start_date'         => '2026-09-30',
				'tag'                => 'Colóquio',
				'title'              => 'O Universo como laboratório de física fundamental: de MeV a ZeV',
				'description'        => 'O colóquio abordará como a física de astropartículas utiliza o Universo para investigar fenômenos extremos e buscar sinais de nova física, com destaque para neutrinos, raios cósmicos, matéria escura e ondas gravitacionais. Serão apresentados resultados e perspectivas dos experimentos Pierre Auger, DUNE e GRAND.',
				'page_source_id'     => 1432,
				'image_attachment_id' => 1431,
			],
			[
				'start_date'         => '2026-09-08',
				'end_date'           => '2026-09-10',
				'tag'                => 'Workshop',
				'title'              => '2º Workshop de Óptica Aplicada da UERJ 2026',
				'description'        => 'Entre os dias 8 e 10 de setembro de 2026, o Instituto de Física da UERJ reunirá estudantes, pesquisadores e profissionais para discutir avanços, aplicações e temas emergentes da Óptica Aplicada, com atividades científicas, intercâmbio de experiências e apresentação de trabalhos.',
				'url'                => 'https://doity.com.br/2o-workshop-de-ptica-aplicada-da-uerj',
				'image_attachment_id' => 1416,
				'class'              => 'fisica-events-card--optics-workshop',
				'external'           => true,
			],
			[
				'start_date'     => '2026-09-14',
				'end_date'       => '2026-09-18',
				'tag'            => 'Evento internacional',
				'title'          => 'RPC 2026 na UERJ abre inscrições e submissão de resumos',
				'description'    => 'UERJ sediará pela primeira vez no Brasil e na América do Sul a International Conference on Resistive Plate Chambers and Related Detectors (RPC 2026), reunindo pesquisadores e especialistas de diversos países.',
				'page_source_id' => 1101,
				'image_url'      => fisica_site_url( '/wp-content/uploads/2026/07/rpc_2026_card_desktop_4x3.jpg' ),
			],
		];

		return apply_filters( 'fisica_featured_events', $events );
	}
}

if ( ! function_exists( 'fisica_parse_featured_event_date' ) ) {
	/**
	 * Parse and validate an event date in the WordPress timezone.
	 *
	 * @param string $date Date in Y-m-d format.
	 *
	 * @return DateTimeImmutable|null
	 */
	function fisica_parse_featured_event_date( $date ) {
		$date   = trim( (string) $date );
		$parsed = DateTimeImmutable::createFromFormat( '!Y-m-d', $date, wp_timezone() );

		if ( ! $parsed || $parsed->format( 'Y-m-d' ) !== $date ) {
			return null;
		}

		return $parsed;
	}
}

if ( ! function_exists( 'fisica_featured_event_is_current' ) ) {
	/**
	 * Determine whether an event must remain visible through its final day.
	 *
	 * @param array<string, mixed> $event Event configuration.
	 * @param DateTimeImmutable    $today Current day in the WordPress timezone.
	 *
	 * @return bool
	 */
	function fisica_featured_event_is_current( $event, $today = null ) {
		$start_date = fisica_parse_featured_event_date( $event['start_date'] ?? '' );

		if ( ! $start_date ) {
			return false;
		}

		$end_date = empty( $event['end_date'] )
			? $start_date
			: fisica_parse_featured_event_date( $event['end_date'] );

		if ( ! $end_date || $end_date < $start_date ) {
			return false;
		}

		if ( ! $today instanceof DateTimeImmutable ) {
			$today = current_datetime()->setTime( 0, 0, 0 );
		}

		return $today <= $end_date;
	}
}

if ( ! function_exists( 'fisica_get_featured_event_url' ) ) {
	/**
	 * Resolve an event URL for both local and deployed databases.
	 *
	 * @param array<string, mixed> $event Event configuration.
	 *
	 * @return string
	 */
	function fisica_get_featured_event_url( $event ) {
		if ( ! empty( $event['url'] ) ) {
			return (string) $event['url'];
		}

		if ( empty( $event['page_source_id'] ) ) {
			return '';
		}

		$page_id = fisica_resolve_deployed_post_id( (int) $event['page_source_id'], 'page' );

		return $page_id ? (string) get_permalink( $page_id ) : '';
	}
}

if ( ! function_exists( 'fisica_get_featured_event_image_url' ) ) {
	/**
	 * Resolve an event image URL for both local and deployed databases.
	 *
	 * @param array<string, mixed> $event Event configuration.
	 *
	 * @return string
	 */
	function fisica_get_featured_event_image_url( $event ) {
		if ( ! empty( $event['image_url'] ) ) {
			return (string) $event['image_url'];
		}

		if ( empty( $event['image_attachment_id'] ) ) {
			return '';
		}

		$attachment_id = fisica_resolve_deployed_post_id( (int) $event['image_attachment_id'], 'attachment' );

		return $attachment_id ? (string) wp_get_attachment_image_url( $attachment_id, 'full' ) : '';
	}
}

if ( ! function_exists( 'shortcode_fisica_eventos_destaques' ) ) {
	/**
	 * Render the home page events section and omit events after their final day.
	 *
	 * @return string
	 */
	function shortcode_fisica_eventos_destaques() {
		$today  = current_datetime()->setTime( 0, 0, 0 );
		$events = array_values(
			array_filter(
				fisica_get_featured_events(),
				static function ( $event ) use ( $today ) {
					return is_array( $event ) && fisica_featured_event_is_current( $event, $today );
				}
			)
		);

		ob_start();
		?>
		<section class="fisica-home-section" data-featured-events>
			<div class="fisica-home-section__head">
				<div>
					<span class="fisica-home-section__eyebrow">Eventos e Destaques</span>
					<!--<h2>Acontece no instituto</h2>-->
					<!--<p>Um bloco mais organizado para divulgar atividades, imagens e acontecimentos relevantes da comunidade acadêmica.</p>-->
				</div>
			</div>

			<div class="fisica-events-grid">
				<?php foreach ( $events as $event ) : ?>
					<?php
					$event_url = fisica_get_featured_event_url( $event );
					$image_url = fisica_get_featured_event_image_url( $event );
					$end_date  = empty( $event['end_date'] ) ? '' : (string) $event['end_date'];
					$classes   = [ 'fisica-events-card' ];

					if ( ! empty( $event['class'] ) ) {
						$classes[] = sanitize_html_class( (string) $event['class'] );
					}

					if ( '' === $event_url || '' === $image_url ) {
						continue;
					}
					?>
					<a class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
						href="<?php echo esc_url( $event_url ); ?>"
						data-event-start="<?php echo esc_attr( $event['start_date'] ); ?>"
						<?php if ( '' !== $end_date ) : ?>data-event-end="<?php echo esc_attr( $end_date ); ?>"<?php endif; ?>
						<?php if ( ! empty( $event['external'] ) ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>
						style="background-image:url('<?php echo esc_url( $image_url ); ?>');">
						<div class="fisica-events-card__content">
							<span class="fisica-events-card__tag"><?php echo esc_html( $event['tag'] ); ?></span>
							<h3><?php echo esc_html( $event['title'] ); ?></h3>
							<p><?php echo esc_html( $event['description'] ); ?></p>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
		<script>
		(() => {
			const section = document.currentScript.previousElementSibling;
			if (!section || !section.matches('[data-featured-events]')) return;
			const parts = new Intl.DateTimeFormat('en-CA', {
				timeZone: 'America/Sao_Paulo',
				year: 'numeric',
				month: '2-digit',
				day: '2-digit'
			}).formatToParts(new Date());
			const datePart = (type) => parts.find((part) => part.type === type)?.value || '';
			const today = `${datePart('year')}-${datePart('month')}-${datePart('day')}`;
			section.querySelectorAll('[data-event-start]').forEach((card) => {
				const finalDate = card.dataset.eventEnd || card.dataset.eventStart;
				if ( finalDate && finalDate < today ) card.remove();
			});
		})();
		</script>
		<?php

		return (string) ob_get_clean();
	}
}
add_shortcode( 'fisica_eventos_destaques', 'shortcode_fisica_eventos_destaques' );
