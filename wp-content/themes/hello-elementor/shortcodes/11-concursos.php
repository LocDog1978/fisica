<?php
/**
 * Selection notices displayed in their department sidebars.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function fisica_enqueue_concursos_styles() {
	if ( is_page( fisica_resolve_deployed_post_ids( [ 307, 313 ], 'page' ) ) ) {
		wp_enqueue_style(
			'fisica-concursos',
			get_stylesheet_directory_uri() . '/assets/css/fisica-concursos.css',
			[ 'fisica-custom-theme' ],
			filemtime( get_stylesheet_directory() . '/assets/css/fisica-concursos.css' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'fisica_enqueue_concursos_styles', 20 );

function shortcode_fisica_concurso_dfnae() {
	ob_start();
	?>
	<section class="fisica-concurso-sidebar" aria-labelledby="fisica-concurso-dfnae">
		<h3 id="fisica-concurso-dfnae">PROCESSO SELETIVO SIMPLIFICADO – DFNAE</h3>
		<p>Está disponível para consulta o edital referente ao Processo Seletivo Simplificado do DFNAE, divulgado conforme o cronograma previsto.</p>
		<p class="fisica-concurso-sidebar__period">Conforme o cronograma do processo seletivo, o período destinado à <strong>interposição de recursos ocorrerá de 14 a 19 de outubro de 2026</strong>.</p>
		<div class="fisica-concurso-sidebar__documents" aria-label="Documentos do Processo Seletivo Simplificado do DFNAE">
			<a class="fisica-concurso-sidebar__button" href="<?php echo esc_url( fisica_site_url( '/wp-content/uploads/2026/10/Edital_Processo_Seletivo_Substituto_DFNAE_2026_v2.docx-1.pdf' ) ); ?>">Acessar edital</a>
			<a class="fisica-concurso-sidebar__button" href="<?php echo esc_url( fisica_site_url( '/wp-content/uploads/2026/10/ATA-ProcessoSimplificado-_EditalN01_2026__282_29_a_261013_100042.pdf' ) ); ?>">Relação dos Candidatos que Atenderam às Exigências do Edital</a>
		</div>
		<div class="fisica-concurso-sidebar__documents">
			<h3>EDITAL E RESULTADO FINAL DA HOMOLOGAÇÃO DAS INSCRIÇÕES DO CONCURSO PARA PROFESSOR SUBSTITUTO DO DFNAE</h3>
			<a class="fisica-concurso-sidebar__button" href="<?php echo esc_url( fisica_site_url( '/wp-content/uploads/2026/10/ATA-ProcessoSimplificado_RFI_EditalN01_2026-1_assinado_assinado_assinado_assinado.pdf' ) ); ?>">Acessar PDF</a>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'fisica_concurso_dfnae', 'shortcode_fisica_concurso_dfnae' );

function shortcode_fisica_concurso_dft() {
	ob_start();
	?>
	<section class="fisica-concurso-sidebar" aria-labelledby="fisica-concurso-dft">
		<h3 id="fisica-concurso-dft">Concurso para Professor Adjunto (40h) – DFT/IFUERJ</h3>
		<p>A Direção do IFADT solicita a divulgação do edital para o concurso de <strong>Professor Adjunto, regime de 40 horas, do Departamento de Física Teórica – DFT/IFUERJ</strong>.</p>
		<ul class="fisica-concurso-sidebar__details">
			<li><strong>Inscrições:</strong> de 01/09/2026 a 01/10/2026, por meio do <strong>PROSSIM</strong>.</li>
			<li><strong>Salário base inicial:</strong> R$ 6.950,85.</li>
			<li>Com <strong>RDE (+65%)</strong>, a remuneração poderá chegar a <strong>R$ 11.468,90</strong>.</li>
			<li>Poderão existir adicionais previstos institucionalmente, incluindo a <strong>bolsa PROCIÊNCIA de R$ 5.000,00 mensais</strong>, quando aplicável.</li>
		</ul>
		<p>O edital completo contém o cronograma e as demais informações do concurso.</p>
		<div class="fisica-concurso-sidebar__documents" aria-label="Documento do concurso para Professor Adjunto do DFT/IFUERJ">
			<a class="fisica-concurso-sidebar__button" href="<?php echo esc_url( fisica_site_url( '/wp-content/uploads/2026/10/Edital-DFT-UERJ-2026-divulgacao-1.pdf' ) ); ?>">Acessar edital completo</a>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'fisica_concurso_dft', 'shortcode_fisica_concurso_dft' );
