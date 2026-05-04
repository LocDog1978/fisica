<?php
/**
 * Shortcode: [icones_servicos_uerj]
 * Description: Exibe os laboratórios com um layout visual mais refinado.
 */

function shortcode_icones_servicos_uerj() {
    $servicos = [
        [
            'img' => get_stylesheet_directory_uri() . '/assets/images/laboratorios/Laboratorios-da-Fisica-03-12-2024-16-card.jpg',
            'desc' => 'Laboratório de Ensino de Física',
            'tooltip' => 'Conheça o Laboratório de Ensino de Física.',
            'link' => 'http://localhost/fisica/index.php/fotos-lef/',
        ],
        [
            'img' => get_stylesheet_directory_uri() . '/assets/images/laboratorios/Laboratorios-da-Fisica-05-12-2024-11-card.jpg',
            'desc' => 'Laboratório de Física Nuclear e Partículas',
            'tooltip' => 'Conheça o Laboratório de Física Nuclear e Partículas.',
            'link' => 'http://localhost/fisica/index.php/fotos-lfpn/',
        ],
        [
            'img' => get_stylesheet_directory_uri() . '/assets/images/laboratorios/GEO_4052-2-card.jpg',
            'desc' => 'Laboratório de Física Moderna',
            'tooltip' => 'Conheça o Laboratório de Física Moderna.',
            'link' => 'http://localhost/fisica/index.php/fotos-lfm/',
        ],
        [
            'img' => get_stylesheet_directory_uri() . '/assets/images/laboratorios/GEO_2732-2-card.jpg',
            'desc' => 'Laboratório de Física Médica',
            'tooltip' => 'Conheça o Laboratório de Física Médica.',
            'link' => 'http://localhost/fisica/index.php/fotos-lfmedicas',
        ],
        [
            'img' => get_stylesheet_directory_uri() . '/assets/images/laboratorios/GEO_5776-2-card.jpg',
            'desc' => 'Laboratório HEPGrid',
            'tooltip' => 'Conheça o laboratório HEPGrid.',
            'link' => 'http://localhost/fisica/index.php/fotos-hepgrid/',
        ],
        [
            'img' => get_stylesheet_directory_uri() . '/assets/images/laboratorios/GEO_2643-2-card.jpg',
            'desc' => 'Laboratório LIETA',
            'tooltip' => 'Conheça o laboratório LIETA.',
            'link' => 'http://localhost/fisica/index.php/fotos-lieta',
        ],
        [
            'img' => get_stylesheet_directory_uri() . '/assets/images/laboratorios/GEO_4437-2-card.jpg',
            'desc' => 'Laboratório de Física e Eletricidade',
            'tooltip' => 'Conheça o Laboratório de Física e Eletricidade.',
            'link' => 'http://localhost/fisica/index.php/fotos-lfe/',
        ],
    ];

    $linhas = [
        array_slice( $servicos, 0, 4 ),
        array_slice( $servicos, 4, 3 ),
    ];

    ob_start();
    ?>
    <section class="fisica-servicos" aria-labelledby="fisica-servicos-title">
        <div class="fisica-servicos__header">
            <span class="fisica-servicos__eyebrow">Laboratórios</span>
            <!-- <h2 class="fisica-servicos__title" id="fisica-servicos-title">Infraestrutura acadêmica com apresentação mais clara e profissional</h2> -->
            <!-- <p class="fisica-servicos__intro">Explore os laboratórios do Instituto em um bloco visual mais elegante, com foco em leitura, hierarquia e navegação.</p> -->
        </div>

        <div class="grid-linhas-servicos">
            <?php foreach ( $linhas as $index => $linha ) : ?>
                <div class="linha-servicos<?php echo 1 === $index ? ' linha-servicos--compacta' : ''; ?>">
                    <?php foreach ( $linha as $servico ) : ?>
                        <?php echo icone_servico_html( $servico ); ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php

    return ob_get_clean();
}

function icone_servico_html( $servico ) {
    $img     = esc_url( $servico['img'] );
    $desc    = esc_html( $servico['desc'] );
    $tooltip = esc_attr( $servico['tooltip'] ?? $desc );
    $link    = esc_url( $servico['link'] ?? '#' );
    $hint    = esc_html( $servico['hint'] ?? '' );

    return "
    <article class='icone-servico' data-tooltip=\"{$tooltip}\">
        <a href=\"{$link}\" class=\"icone-link\" aria-label=\"Acessar {$desc}\">
            <div class='icone-servico__card'>
                <div class='circulo-wrapper'>
                    <div class='anel-fixo'></div>
                    <div class='anel-animado'></div>
                    <div class='circulo-interno'>
                        <img src=\"{$img}\" alt=\"{$desc}\" loading=\"lazy\" decoding=\"async\" width=\"512\" height=\"341\">
                    </div>
                </div>
                <p class='descricao-servico'>{$desc}</p>
                <p class='icone-servico__hint'>{$hint}</p>
            </div>
        </a>
    </article>
    ";
}
add_shortcode( 'icones_servicos_uerj', 'shortcode_icones_servicos_uerj' );
