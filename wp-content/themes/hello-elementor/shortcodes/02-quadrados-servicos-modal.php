<?php
/**
 * Shortcode: [quadrados_servicos_link]
 * Description: Exibe áreas, programas e links internos em cards mais sofisticados.
 */

function shortcode_quadrados_servicos_link() {
    $servicos = [
        [
            'titulo' => 'Cosmologia e Gravitação',
            'descricao' => 'Exploração de temas ligados à estrutura do universo e a teorias gravitacionais.',
            'link' => fisica_site_url( '/index.php/gravitacao/' ),
        ],
        [
            'titulo' => 'Ensino de Física e Educação Científica',
            'descricao' => 'Iniciativas para formação docente, didática e práticas educacionais contemporâneas.',
            'link' => fisica_site_url( '/index.php/ensino-de-fisico/' ),
        ],
        [
            'titulo' => 'Física Experimental de Altas Energias',
            'descricao' => 'Projetos experimentais conectados a grandes colaborações e detectores.',
            'link' => fisica_site_url( '/index.php/altas-energias/' ),
        ],
        [
            'titulo' => 'Física Hadrônica, Nuclear e Teoria Quântica de Campos',
            'descricao' => 'Estudos teóricos e nucleares com base matemática robusta e pesquisa fundamental.',
            'link' => fisica_site_url( '/index.php/teoria-quantica/' ),
        ],
        [
            'titulo' => 'Física Matemática e Computacional de Alto Desempenho',
            'descricao' => 'Abordagens formais, modelagem e computação aplicada à física contemporânea.',
            'link' => fisica_site_url( '/index.php/matematica/' ),
        ],
        [
            'titulo' => 'Física da Matéria Condensada Experimental',
            'descricao' => 'Investigações experimentais em materiais, propriedades físicas e sistemas complexos.',
            'link' => fisica_site_url( '/index.php/materia-condensada-experimental/' ),
        ],
        [
            'titulo' => 'Física Médica',
            'descricao' => 'Pesquisas interdisciplinares com interface entre física, saúde e aplicações biomédicas.',
            'link' => fisica_site_url( '/index.php/ciencias-biomedicas-ambientais/' ),
        ],
        [
            'titulo' => 'Instrumentação Eletrônica e Técnicas Analíticas',
            'descricao' => 'Soluções em instrumentação, análise e tecnologias aplicadas para pesquisa e desenvolvimento.',
            'link' => fisica_site_url( '/index.php/aplicacoes-industriais/' ),
        ],
    ];

    ob_start();
    ?>
    <section class="fisica-programas" aria-labelledby="fisica-programas-title">
        <div class="fisica-programas__header">
            <span class="fisica-programas__eyebrow">Pesquisa e Desenvolvimento</span>
            <!-- <h2 class="fisica-programas__title" id="fisica-programas-title">Conteúdos importantes organizados em uma navegação mais forte</h2> -->
            <!-- <p class="fisica-programas__intro">Os links foram reorganizados em cards com melhor hierarquia visual, leitura mais fluida e destaque claro para a navegação interna.</p> -->
        </div>

        <div class="grid-quadrados-servicos">
            <?php foreach ( $servicos as $index => $servico ) : ?>
                <?php echo quadrado_servico_link_html( $servico, $index + 1 ); ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php

    return ob_get_clean();
}
add_shortcode( 'quadrados_servicos_link', 'shortcode_quadrados_servicos_link' );

function quadrado_servico_link_html( $servico, $indice ) {
    $titulo    = esc_html( $servico['titulo'] );
    $descricao = esc_html( $servico['descricao'] ?? '' );
    $link      = esc_url( $servico['link'] ?? '#' );
    $badge     = esc_html( str_pad( (string) $indice, 2, '0', STR_PAD_LEFT ) );

    return "
    <a href='{$link}' class='quadrado-servico' aria-label='Acessar {$titulo}'>
        <div class='quadrado-conteudo'>
            <span class='quadrado-servico__indice'>{$badge}</span>
            <h3>{$titulo}</h3>
            <p>{$descricao}</p>
            <span class='quadrado-servico__cta'>Explorar conteúdo</span>
        </div>
    </a>";
}
