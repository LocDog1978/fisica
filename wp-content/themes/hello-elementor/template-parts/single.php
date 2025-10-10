<?php
/**
 * Template Part - Single
 * Responsável por exibir o conteúdo principal das páginas/postagens.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<main id="site-content" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content(); // 👈 ESTA É A LINHA FUNDAMENTAL
	endwhile;
	?>
</main>

