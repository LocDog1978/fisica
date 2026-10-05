<?php
/**
 * VI EPSBF article, using the existing internal news components.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function shortcode_fisica_epsbf_2026() {
	$post = get_post();
	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	$attachment_ids = fisica_resolve_deployed_post_ids( [ 1466, 1467, 1465, 1463, 1464, 1462, 1460, 1461, 1459, 1458 ], 'attachment' );
	$render_pair = static function ( $offset ) use ( $attachment_ids ) {
		echo '<div class="fisica-news-article__gallery-grid" aria-label="Registros do VI Encontro de Primavera">';
		foreach ( array_slice( $attachment_ids, $offset, 2 ) as $index => $attachment_id ) {
			echo wp_kses_post( fisica_render_internal_news_gallery_figure( $attachment_id, 'gallery', 'Participação da UERJ no VI Encontro de Primavera da Sociedade Brasileira de Física — registro ' . ( $offset + $index + 1 ) ) );
		}
		echo '</div>';
	};

	ob_start();
	?>
	<section class="fisica-news-article fisica-news-article--wide-copy fisica-news-article--epsbf-2026">
		<div class="fisica-news-article__hero">
			<div class="fisica-news-article__hero-card">
				<span class="fisica-news-article__eyebrow">Evento científico</span>
				<h1 class="fisica-news-article__title"><?php echo esc_html( get_the_title( $post ) ); ?></h1>
			</div>
		</div>
		<div class="fisica-news-article__wrap">
			<article class="fisica-news-article__main" aria-label="Conteúdo da notícia">
				<div class="fisica-news-article__panel">
					<div class="fisica-news-article__meta">
						<span class="fisica-news-article__meta-item">VI EPSBF</span>
						<span class="fisica-news-article__meta-item"><?php echo esc_html( get_the_date( 'j \d\e F \d\e Y', $post ) ); ?></span>
					</div>
					<section class="fisica-news-article__section fisica-news-article__body">
						<p><strong>UERJ participa do VI Encontro de Primavera da Sociedade Brasileira de Física</strong></p>
						<p>Entre os dias 22 e 25 de setembro de 2026, estudantes, professores e pesquisadores da Universidade do Estado do Rio de Janeiro (UERJ) participaram do VI Encontro de Primavera da Sociedade Brasileira de Física (EPSBF), realizado no campus de Goiabeiras da Universidade Federal do Espírito Santo (UFES), em Vitória (ES). O encontro reuniu trabalhos nas áreas de Física Nuclear, Física Experimental de Altas Energias, Divulgação e Ensino da Física Nuclear e de Partículas Elementares e Teoria Quântica de Campos, com apresentações em diferentes modalidades.</p>
						<?php $render_pair( 0 ); ?>
					</section>
					<section class="fisica-news-article__section fisica-news-article__body">
						<h2>Divulgação e Ensino da Física Nuclear e de Partículas Elementares (DCE)</h2>
						<p>Na área de Divulgação e Ensino da Física Nuclear e de Partículas Elementares, a UERJ apresentou trabalhos voltados à divulgação científica, ao ensino de Física de Partículas e ao desenvolvimento de recursos pedagógicos.</p>
						<ul>
							<li>Professora Márcia Begalli — “ATLAS Virtual Visits - your first journey into the particle physics world and CERN” — Comunicação oral.</li>
							<li>Professora Márcia Begalli — “IPPOG Masterclasses - Hands on Particle Physics in Brasil” — Comunicação oral.</li>
							<li>Julya Pacheco Ferreira — “PIRARI: A Digital Board Game for Particle Physics Outreach and Education” — Comunicação oral.</li>
							<li>Lavínia Ferraz de Lima — “Translating ATLAS: The importance of overcoming the language barrier to improve science education” — Pôster.</li>
							<li>Letícia de Souza James — “CELESTE Project: Cosmic Ray Detector Network, Development of Educational Materials, and Air Shower Simulations in High School Education” — Pôster.</li>
							<li>Pedro Henrique da Silva Oliveira — “Translation of the book ‘Teilchenphysik – Eine Theorie der Ladungen und Wechselwirkungen’ as a pedagogical resource for Particle Physics education” — Pôster.</li>
							<li>Pedro Henrique da Silva Oliveira — “HypatiaMobile: A Portable Physics Analysis Tool for Particle Physics Education” — Pôster — premiado.</li>
						</ul>
						<p>Um dos destaques da participação da UERJ foi o estudante Pedro Henrique da Silva Oliveira, premiado pela apresentação do trabalho “HypatiaMobile: A Portable Physics Analysis Tool for Particle Physics Education”.</p>
						<?php $render_pair( 2 ); ?>
					</section>
					<section class="fisica-news-article__section fisica-news-article__body">
						<h2>Física Experimental de Altas Energias (HEX)</h2>
						<p>Na área de Física Experimental de Altas Energias, os trabalhos da UERJ contemplaram estudos relacionados aos experimentos CMS e ATLAS, desenvolvimento e desempenho de detectores, física de mésons, análise de dados e aplicações de aprendizado de máquina.</p>
						<p class="fisica-epsbf-subheading"><strong>Apresentações paralelas:</strong></p>
						<ul>
							<li>Professor Dilson de Jesus Damião — “Brazilian participation in the CMS RPC system and its readiness for the HL-LHC” — Apresentação paralela.</li>
							<li>Professora Marisilvia Donadelli — “Search for Higgs pair production: deciphering the nature of the Higgs potential at the LHC” — Apresentação paralela.</li>
						</ul>
						<p class="fisica-epsbf-subheading"><strong>Comunicações orais:</strong></p>
						<ul>
							<li>Silas Santos de Jesus — “Study of Central Diffractive Production of D and D0 Mesons in Proton–Proton Collisions at √s=13 TeV with CMS and TOTEM”* — Comunicação oral.</li>
							<li>Professor Maurício Thiel — “Operation and performance of the CMS RPC system during LHC Run 3 and preparations for Run 4” — Comunicação oral.</li>
							<li>Professora Marisilvia Donadelli — “Searches for Higgs boson pair production with ATLAS: current results and HL-LHC prospects” — Comunicação oral.</li>
							<li>Dalmo da Silva Dalto — “Study of iRPC waveforms and classification of signals using Machine Learning” — Comunicação oral.</li>
							<li>Katherine Maslova — “Development of a Cosmic Muon Trigger System for CERN-GIF++” — Comunicação oral.</li>
							<li>Julya Pacheco Ferreira — “Implementation of a Muon Spectrometer in the Lorenzetti Framework” — Comunicação oral.</li>
						</ul>
						<?php $render_pair( 4 ); ?>
						<p class="fisica-epsbf-subheading"><strong>Pôsteres:</strong></p>
						<ul>
							<li>Gabriel Campanelli — “Development of RPC Detector Components with Low-Cost Alternative Materials” — Pôster.</li>
							<li>Allan da Silva Jales — “Performance and longevity of CO2-based mixtures in CMS Improved Resistive Plate Chambers in the HL-LHC environment” — Pôster.</li>
							<li>Lucas Johnny Monte Tamayo — “Application of machine learning techniques for event classification at the FCC: a study of the B_c meson decay” — Pôster.</li>
							<li>João de Felipe Andrade — “Parallel and vectorized B-meson decay reconstruction using CMS OpenData Run 2 samples” — Pôster.</li>
							<li>João Pedro Gomes Pinheiro — “Improved Resistive Plate Chambers for Phase-II upgrade of the CMS detector at LHC” — Pôster.</li>
							<li>Lucas Brasil de Cerqueira — “Study of the associated production of upsilon pairs with CMS public data from the Run 2 of the LHC” — Pôster.</li>
							<li>Lucas Johnny Monte Tamayo — “Associated Production of J/ψ and D in pp Collisions at √s = 13 TeV in the CMS detector”* — Pôster — premiado.</li>
						</ul>
						<?php $render_pair( 6 ); ?>
						<ul>
							<li>Matheus Figueiredo de Paiva Nascimento — “Associated Production of ψ(2S) + D in Double Parton Scattering Events at √s = 13 TeV in the CMS Experiment”* — Pôster.</li>
							<li>Nathalia Montenegro Guimarães Moraes — “Use of Monte Carlo Simulation as a Tool for the Analysis of the H → Zγ Decay” — Pôster.</li>
							<li>Thiago de Andrade Rangel Monteiro — “Medida da Fração de Ramificação do Decaimento B0→K0(892)μ+μ− Utilizando Dados do Experimento CMS a √s=13,6 TeV”* — Pôster.</li>
							<li>Thiago Henrique de Sousa — “Parametric Neural Networks in the Search for Dark Matter in proton-proton collisions at 13.6 TeV in the Compact Muon Solenoid” — Pôster.</li>
							<li>Victor Almeida de Assis — “NeuralRinger’s Study for Taus in the ATLAS experiment” — Pôster.</li>
							<li>Victor Almeida de Assis — “Recent ML developments using GNN in the search for HH -> bbtautau with Run 2 and partial Run 3 data in ATLAS experiment” — Pôster.</li>
							<li>Dalmo da Silva Dalto — “Study of RPC waveforms parameters and classification of signals using Machine Learning” — Pôster.</li>
						</ul>
						<p>Entre os trabalhos apresentados na modalidade pôster, Lucas Johnny Monte Tamayo recebeu premiação por “Associated Production of J/ψ and D in pp Collisions at √s = 13 TeV in the CMS detector”*.</p>
					</section>
					<section class="fisica-news-article__section fisica-news-article__body">
						<h2>Teoria Quântica de Campos (QFT)</h2>
						<p>Na área de Teoria Quântica de Campos, a UERJ também esteve representada por trabalhos envolvendo fundamentos da teoria quântica e análise matemática.</p>
						<ul>
							<li>João Gabriel Alencar Caribé — “Modular Theory and the Bell-CHSH inequality in relativistic scalar Quantum Field Theory” — Comunicação oral.</li>
							<li>Nicolas Alves Botelho e Silva — “ASYMPTOTIC ANALYSIS OF THE AIRY FUNCTION” — Pôster.</li>
						</ul>
						<?php $render_pair( 8 ); ?>
					</section>
					<section class="fisica-news-article__section fisica-news-article__body">
						<h2>Participação da UERJ</h2>
						<p>A participação da UERJ no VI EPSBF reuniu estudantes, professores e pesquisadores em diferentes áreas da Física, evidenciando a diversidade das pesquisas desenvolvidas na universidade. Ao todo, foram 10 comunicações orais, 19 apresentações em formato de pôster e 2 apresentações paralelas, com participação de estudantes de graduação, mestrado e doutorado, além de professores e pesquisadores da instituição.</p>
						<p>Os trabalhos apresentados contemplaram desde o desenvolvimento de detectores e análises de dados dos experimentos do LHC até estudos de Física de Partículas, aprendizado de máquina, Teoria Quântica de Campos e iniciativas de divulgação e ensino.</p>
						<p>A participação estudantil também foi marcada pelas duas premiações recebidas por estudantes da UERJ: Pedro Henrique da Silva Oliveira, na área de Divulgação e Ensino da Física Nuclear e de Partículas Elementares, pelo trabalho “HypatiaMobile: A Portable Physics Analysis Tool for Particle Physics Education”, e Lucas Johnny Monte Tamayo, na área de Física Experimental de Altas Energias, pelo trabalho “Associated Production of J/ψ and D in pp Collisions at √s = 13 TeV in the CMS detector”*.</p>
					</section>
				</div>
			</article>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'fisica_epsbf_2026', 'shortcode_fisica_epsbf_2026' );

function fisica_enqueue_epsbf_2026_styles() {
	if ( ! is_singular( 'page' ) || ! has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'fisica_epsbf_2026' ) ) {
		return;
	}
	wp_enqueue_style(
		'fisica-epsbf-2026',
		get_stylesheet_directory_uri() . '/assets/css/fisica-epsbf-2026.css',
		[ 'fisica-custom-theme' ],
		filemtime( get_stylesheet_directory() . '/assets/css/fisica-epsbf-2026.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'fisica_enqueue_epsbf_2026_styles', 30 );

function fisica_hide_epsbf_2026_default_title( $show_title ) {
	if ( ! is_admin() && is_singular( 'page' ) && has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'fisica_epsbf_2026' ) ) {
		return false;
	}
	return $show_title;
}
add_filter( 'hello_elementor_page_title', 'fisica_hide_epsbf_2026_default_title', 20 );
