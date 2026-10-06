<?php
/**
 * Concurso announcement for the home page.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function shortcode_fisica_home_concursos() {
	ob_start();
	?>
	<style>
		.fisica-home-concursos { width: 100%; }
		.fisica-home-concursos__card {
			display: grid;
			grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
			gap: clamp(22px, 3vw, 36px);
			padding: clamp(20px, 3vw, 30px);
			border: 1px solid var(--fisica-border);
			border-radius: var(--fisica-radius-lg);
			background: var(--fisica-card);
			color: var(--fisica-text);
		}
		.fisica-home-concursos__card > * { min-width: 0; }
		.fisica-home-concursos__card h3 {
			margin: 0 0 16px;
			color: var(--fisica-primary-strong);
			font-size: clamp(22px, 2vw, 28px);
			line-height: 1.24;
			text-wrap: balance;
		}
		.fisica-home-concursos__card p,
		.fisica-home-concursos__card li {
			font-size: 16px;
			line-height: 1.7;
		}
		.fisica-home-concursos__card p { margin: 0; }
		.fisica-home-concursos__inscricoes {
			margin-top: 20px;
			padding: 16px 18px;
			border-radius: var(--fisica-radius-md);
			background: var(--fisica-surface);
			border-left: 3px solid var(--fisica-primary);
		}
		.fisica-home-concursos__detalhes {
			display: flex;
			flex-direction: column;
			align-items: flex-start;
			gap: 14px;
			padding-left: clamp(22px, 3vw, 36px);
			border-left: 1px solid var(--fisica-border);
		}
		.fisica-home-concursos__card h4 {
			margin: 0;
			font-size: 18px;
			line-height: 1.4;
			color: var(--fisica-primary-strong);
		}
		.fisica-home-concursos__detalhes ul {
			margin: 0;
			padding-left: 20px;
		}
		.fisica-home-concursos__detalhes li + li { margin-top: 10px; }
		.fisica-home-concursos__edital { margin-top: 8px; }
		.fisica-home-concursos__card a {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-height: 48px;
			max-width: 100%;
			margin-top: 12px;
			padding: 12px 22px;
			border-radius: var(--fisica-radius-pill);
			background: var(--fisica-primary);
			color: #ffffff;
			font-size: 14px;
			font-weight: 700;
			line-height: 1.5;
			text-align: center;
			text-decoration: none;
			transition: background var(--fisica-transition);
		}
		.fisica-home-concursos__card a:hover {
			background: var(--fisica-primary-strong);
			color: #ffffff;
		}
		.fisica-home-concursos__card a:focus-visible {
			outline: 3px solid var(--fisica-accent);
			outline-offset: 3px;
		}
		@media (max-width: 767px) {
			.fisica-home-concursos__card { grid-template-columns: minmax(0, 1fr); padding: 20px 16px; }
			.fisica-home-concursos__detalhes { padding: 20px 0 0; border-left: 0; border-top: 1px solid var(--fisica-border); }
			.fisica-home-concursos__card p,
			.fisica-home-concursos__card li { font-size: 15px; line-height: 1.65; }
			.fisica-home-concursos__inscricoes { padding: 14px; }
		}
	</style>
	<section id="concursos" class="fisica-home-section fisica-home-concursos" aria-labelledby="concursos-titulo">
		<div class="fisica-home-section__head">
			<h2 id="concursos-titulo">Concursos</h2>
		</div>
		<article class="fisica-home-concursos__card" aria-labelledby="concurso-dft-titulo">
			<div>
				<h3 id="concurso-dft-titulo">Concurso para Professor Adjunto – DFT/UERJ</h3>
				<p>O Instituto de Física Armando Dias Tavares (IFADT/UERJ) divulga edital de concurso público para o cargo de <strong>Professor Adjunto, com carga horária de 40 horas semanais</strong>, para atuação no <strong>Departamento de Física Teórica (DFT/UERJ)</strong>.</p>
				<div class="fisica-home-concursos__inscricoes">
					<p>As inscrições estarão abertas no período de <strong><time datetime="2026-09-01">01 de setembro de 2026</time> a <time datetime="2026-10-01">01 de outubro de 2026</time></strong>, por meio do sistema <strong>PROSSIM</strong>.</p>
				</div>
			</div>
			<div class="fisica-home-concursos__detalhes">
				<h4>Remuneração inicial:</h4>
				<ul>
					<li>Salário-base: <strong>R$ 6.950,85</strong>.</li>
					<li>Com Regime de Dedicação Exclusiva (RDE), correspondente a adicional de 65%: <strong>remuneração bruta inicial de R$ 11.468,90</strong>.</li>
				</ul>
				<div class="fisica-home-concursos__edital">
					<h4>Edital completo</h4>
					<a href="<?php echo esc_url( fisica_site_url( '/wp-content/uploads/2026/10/Edital-DFT-UERJ-2026-divulgacao.pdf' ) ); ?>">Acessar edital completo</a>
				</div>
			</div>
		</article>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'fisica_home_concursos', 'shortcode_fisica_home_concursos' );
