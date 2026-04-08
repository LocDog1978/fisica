<?php
/**
 * Shortcode: [quadrados_servicos_link]
 * Description: Exibe areas, programas e links internos em cards mais sofisticados.
 */

function shortcode_quadrados_servicos_link() {
    $servicos = [
        [
            'titulo' => 'Física Aplicada às Ciências Biomédicas e Ambientais',
            'descricao' => 'Pesquisas interdisciplinares com impacto em saúde, meio ambiente e inovação.',
            'link' => 'http://localhost/fisica/index.php/ciencias-biomedicas-ambientais/',
        ],
        [
            'titulo' => 'Aplicações Industriais de Radioisótopos',
            'descricao' => 'Projetos voltados à indústria, processos e tecnologias nucleares aplicadas.',
            'link' => 'http://localhost/fisica/index.php/aplicacoes-industriais/',
        ],
        [
            'titulo' => 'Ensino de Física na Formação de Professores',
            'descricao' => 'Iniciativas para formação docente, didática e práticas educacionais contemporâneas.',
            'link' => 'http://localhost/fisica/index.php/ensino-de-fisico/',
        ],
        [
            'titulo' => 'Física Nuclear Aplicada',
            'descricao' => 'Estudos e aplicações com foco em processos, instrumentação e análise.',
            'link' => 'http://localhost/fisica/index.php/nuclear-aplicada/',
        ],
        [
            'titulo' => 'Física da Matéria Condensada',
            'descricao' => 'Investigações em materiais, propriedades físicas e sistemas complexos.',
            'link' => 'http://localhost/fisica/index.php/materia-condensada/',
        ],
        [
            'titulo' => 'Magnetismo e Materiais Magnéticos',
            'descricao' => 'Pesquisa dedicada a propriedades magnéticas e desenvolvimento de materiais.',
            'link' => 'http://localhost/fisica/index.php/magnetismo/',
        ],
        [
            'titulo' => 'Sensores e Fibra Ótica',
            'descricao' => 'Soluções em sensores avançados, medição e tecnologias fotônicas.',
            'link' => 'http://localhost/fisica/index.php/sensores-e-fibras-oticas/',
        ],
        [
            'titulo' => 'Teoria Quântica de Campos',
            'descricao' => 'Linhas teóricas com base matemática robusta e pesquisa fundamental.',
            'link' => 'http://localhost/fisica/index.php/teoria-quantica/',
        ],
        [
            'titulo' => 'Gravitação e Cosmologia',
            'descricao' => 'Exploração de temas ligados à estrutura do universo e teorias gravitacionais.',
            'link' => 'http://localhost/fisica/index.php/gravitacao/',
        ],
        [
            'titulo' => 'Física Matemática',
            'descricao' => 'Abordagens formais, modelagem e fundamentos da física contemporânea.',
            'link' => 'http://localhost/fisica/index.php/matematica/',
        ],
        [
            'titulo' => 'Física Experimental de Altas Energias',
            'descricao' => 'Projetos experimentais conectados a grandes colaborações e detectores.',
            'link' => 'http://localhost/fisica/index.php/altas-energias/',
        ],
        [
            'titulo' => 'Iniciação Científica',
            'descricao' => 'Entrada qualificada de estudantes em atividades de pesquisa e desenvolvimento.',
            'link' => 'http://localhost/fisica/index.php/iniciacao-cientifica/',
        ],
        [
            'titulo' => 'Monitorias',
            'descricao' => 'Apoio acadêmico com foco em aprendizagem, acompanhamento e prática.',
            'link' => 'http://localhost/fisica/index.php/monitorias/',
        ],
        [
            'titulo' => 'Estágios',
            'descricao' => 'Oportunidades de vivência profissional articuladas com a formação universitária.',
            'link' => 'http://localhost/fisica/index.php/estagios/',
        ],
    ];

    ob_start();
    ?>
    <section class="fisica-programas" aria-labelledby="fisica-programas-title">
        <div class="fisica-programas__header">
            <span class="fisica-programas__eyebrow">Areas e Programas</span>
            <h2 class="fisica-programas__title" id="fisica-programas-title">Conteudos importantes organizados em uma navegacao mais forte</h2>
            <p class="fisica-programas__intro">Os links foram reorganizados em cards com melhor hierarquia visual, leitura mais fluida e destaque claro para a navegação interna.</p>
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
            <span class='quadrado-servico__cta'>Explorar conteudo</span>
        </div>
    </a>";
}
