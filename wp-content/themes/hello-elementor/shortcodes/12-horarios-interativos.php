<?php
/**
 * Interactive weekly timetable for the Horário das Disciplinas page.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function fisica_is_horarios_interativos_page() {
	return is_singular( 'page' ) && has_shortcode(
		(string) get_post_field( 'post_content', get_queried_object_id() ),
		'fisica_horarios_interativos'
	);
}

function fisica_enqueue_horarios_interativos_assets() {
	if ( ! fisica_is_horarios_interativos_page() ) {
		return;
	}

	$directory = get_stylesheet_directory();
	$uri       = get_stylesheet_directory_uri();

	wp_enqueue_style(
		'fisica-horarios-interativos',
		$uri . '/assets/css/fisica-horarios-interativos.css',
		[ 'fisica-custom-theme' ],
		filemtime( $directory . '/assets/css/fisica-horarios-interativos.css' )
	);
	wp_enqueue_script(
		'fisica-horarios-interativos',
		$uri . '/assets/js/fisica-horarios-interativos.js',
		[],
		filemtime( $directory . '/assets/js/fisica-horarios-interativos.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'fisica_enqueue_horarios_interativos_assets', 30 );

function shortcode_fisica_horarios_interativos() {
	$data_path = '/assets/data/horarios_2026_2.json';
	$data_url  = add_query_arg(
		'ver',
		filemtime( get_stylesheet_directory() . $data_path ),
		get_stylesheet_directory_uri() . $data_path
	);

	ob_start();
	?>
	<section class="fisica-horarios" data-fisica-horarios data-source="<?php echo esc_url( $data_url ); ?>" aria-labelledby="fisica-horarios-title" aria-busy="true">
		<div class="horarios-header">
			<h1 id="fisica-horarios-title">Horários das Disciplinas</h1>
			<p>Planejamento acadêmico 2026.2 · Consulte disciplinas, turmas, professores e salas</p>
		</div>
		<div class="wrap">
			<section class="panel">
				<div class="filters">
					<div>
						<label for="fisica-horarios-q">Buscar disciplina, professor ou sala</label>
						<input id="fisica-horarios-q" data-horarios-control="q" placeholder="Ex.: Física Computacional, 3053 F..." autocomplete="off">
					</div>
					<div>
						<label for="fisica-horarios-periodo">Período / grupo</label>
						<select id="fisica-horarios-periodo" data-horarios-control="periodo"><option value="">Todos os períodos e grupos</option></select>
					</div>
					<div>
						<label for="fisica-horarios-dia">Dia da semana</label>
						<select id="fisica-horarios-dia" data-horarios-control="dia"><option value="">Todos os dias</option></select>
					</div>
					<div>
						<label for="fisica-horarios-turno">Turno</label>
						<select id="fisica-horarios-turno" data-horarios-control="turno"><option value="">Todos os turnos</option><option value="M">Manhã</option><option value="T">Tarde</option><option value="N">Noite</option></select>
					</div>
				</div>
				<div class="clear-row"><button type="button" class="clear" data-horarios-clear>Limpar filtros</button></div>
			</section>
			<section class="panel">
				<div class="toolbar">
					<div class="tabs"><button type="button" class="active" aria-pressed="true">Grade semanal</button></div>
					<span class="count" data-horarios-count aria-live="polite">Carregando horários...</span>
				</div>
				<div data-horarios-results></div>
			</section>
			<p class="footer">Fonte: planejamento PT 2026.2 enviado para consolidação. Confira alterações e eventuais lacunas de professor/sala com a coordenação. Dados estáticos: para atualizar, publique uma nova exportação.</p>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'fisica_horarios_interativos', 'shortcode_fisica_horarios_interativos' );

function fisica_hide_horarios_interativos_default_title( $show_title ) {
	return ! is_admin() && fisica_is_horarios_interativos_page() ? false : $show_title;
}
add_filter( 'hello_elementor_page_title', 'fisica_hide_horarios_interativos_default_title', 20 );
