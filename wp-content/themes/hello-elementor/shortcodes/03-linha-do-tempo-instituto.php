<?php
/**
 * Shortcode: [fisica_linha_tempo_instituto]
 * Description: Exibe a linha do tempo do Instituto de Física da UERJ.
 */

function shortcode_fisica_linha_tempo_instituto( $atts = [] ) {
    static $instance = 0;
    $instance++;

    $atts = shortcode_atts(
        [
            'show_header' => 'true',
        ],
        $atts,
        'fisica_linha_tempo_instituto'
    );

    $show_header = filter_var( $atts['show_header'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
    if ( null === $show_header ) {
        $show_header = true;
    }

    $timeline_id = 'fisica-timeline-' . $instance;

    $eventos = [
        [
            'ano' => '1944',
            'titulo' => 'Reconhecimento do Curso de Física',
            'texto' => 'O curso de Física foi oficialmente reconhecido em 1944, quando ainda funcionava no antigo Instituto La-Fayette, situado na Rua Haddock Lobo, nº 253, na Tijuca. O Instituto La-Fayette foi um colégio inovador e progressista para sua época, pioneiro na implantação do jardim de infância e em iniciativas de inclusão social e educacional, como o acolhimento de filhos de pais desquitados, o ecumenismo e a recepção de alunos negros em seu internato. Nesse ambiente de vanguarda, a formação em Física começou a ganhar contornos institucionais, lançando as bases do que viria a ser o futuro Instituto de Física da UERJ.',
        ],
        [
            'ano' => '1954',
            'titulo' => 'Integração à Faculdade de Filosofia, Ciências e Letras',
            'texto' => 'Em 1954, o Instituto de Física foi incorporado à Faculdade de Filosofia, Ciências e Letras (FFCL) da Universidade do Distrito Federal (UDF), denominação inicial da atual UERJ. A UDF havia sido formada pela união de quatro faculdades: Ciências Econômicas, Direito, Ciências Médicas e a própria FFCL, derivada da Faculdade de Filosofia do Instituto La-Fayette. Essa integração ampliou as possibilidades de pesquisa e a formação de professores e pesquisadores em um ambiente acadêmico mais estruturado.',
        ],
        [
            'ano' => '1966',
            'titulo' => 'Criação dos Institutos Básicos da Universidade',
            'texto' => 'Em 1966, foram criados os Institutos Básicos da UERJ, consolidando as atividades de ensino em áreas fundamentais, entre elas a Física. A reorganização foi formalizada pela Resolução nº 296, de 14 de outubro de 1966, que instituiu os Institutos de Física, Química, Matemática e Estatística, Desenho e Artes Aplicadas e Geociências, alinhada ao Plano de Reestruturação da antiga UEG. Diferentemente dos demais, o Instituto de Física já havia se desvinculado da Faculdade de Filosofia, Ciências e Letras, o que lhe conferiu autonomia precoce. Essa separação foi decisiva para seu fortalecimento institucional, permitindo-lhe assumir um papel de destaque na nova estrutura universitária.',
        ],
        [
            'ano' => '1974',
            'titulo' => 'Instalação no Pavilhão João Lyra Filho',
            'texto' => 'Em 1974, o Instituto de Física foi transferido para o terceiro andar do Pavilhão João Lyra Filho, no campus Maracanã, marcando uma nova fase de consolidação física e acadêmica. Nesse espaço, onde permanece até hoje, foram instaladas salas de professores, laboratórios de ensino e pesquisa, biblioteca, salas de aula, o Programa de Pós-Graduação, o CAFIS e auditórios. A convivência cotidiana entre estudantes, docentes e técnicos nesse ambiente dinâmico favoreceu e favorece a troca de ideias, a formação científica e o fortalecimento da integração das atividades de ensino, pesquisa e extensão.',
        ],
        [
            'ano' => '1983',
            'titulo' => 'Primeira Semana de Física da UERJ',
            'texto' => 'A realização da primeira Semana de Física da UERJ, em 1983, representou um marco na integração entre ensino, pesquisa e extensão. Organizada pelo Centro Acadêmico de Física (CAFIS), a Semana de Física se consolidou como evento anual, reunindo palestras, minicursos e atividades voltadas à divulgação científica e à interação entre estudantes, professores e pesquisadores de diferentes áreas e instituições. A iniciativa, que permanece até hoje e chegou, em 2025, à 23ª edição, ajudou a fortalecer o sentimento de pertencimento científico e a visibilidade do Instituto.',
        ],
        [
            'ano' => '1991',
            'titulo' => 'Reestruturação dos Departamentos',
            'texto' => 'Em 1991, o Instituto de Física passou por uma importante reestruturação departamental, que definiu uma nova organização interna: Departamento de Física Teórica (DFT), Departamento de Física Aplicada à Termodinâmica (DFAT), Departamento de Eletrônica Quântica (DEQ) e Departamento de Física Nuclear e Altas Energias (DFNAE). Essa reorganização aprimorou a gestão acadêmica e científica, distribuindo as atividades de ensino e as áreas de atuação de forma mais eficaz, o que contribuiu para o desenvolvimento de grupos de pesquisa mais consolidados e especializados, incluindo a pesquisa em ensino de Física.',
        ],
        [
            'ano' => '1991',
            'titulo' => 'Primeira Reformulação do Curso de Física',
            'texto' => 'No mesmo ano, foi realizada a primeira grande reformulação do curso de Física, um passo decisivo na consolidação do Instituto como espaço de formação profissional. O novo currículo foi planejado para aproximar o ensino das atividades de pesquisa, incentivando a participação dos estudantes em projetos extracurriculares desde as etapas iniciais da graduação. Essa reformulação marcou o início de uma cultura acadêmica voltada à produção de conhecimento e à formação de pesquisadores.',
        ],
        [
            'ano' => '1995',
            'titulo' => 'Criação da Biblioteca do Instituto de Física',
            'texto' => 'A Biblioteca do Instituto de Física foi inaugurada em 1995, no terceiro andar do Pavilhão João Lyra Filho, com o acervo da área desmembrado da Biblioteca CTC/A. O acervo inicial foi enriquecido por doações do Clube de Leitura do Instituto de Física (CLIF) e por bibliotecas particulares de docentes. Logo após sua criação, a biblioteca recebeu seus primeiros computadores e passou a oferecer, em 1997, a base de dados BIB (MicroIsis) para consulta eletrônica. Foi também uma das primeiras da UERJ a climatizar seu espaço, graças à atuação da Comissão de Biblioteca, tornando-se um ambiente de referência para os estudantes.',
        ],
        [
            'ano' => '1997',
            'titulo' => 'Recomendação do Mestrado pela CAPES',
            'texto' => 'Em 1997, a CAPES recomendou o Programa de Mestrado do Instituto de Física da UERJ, reconhecendo a maturidade acadêmica alcançada. A primeira avaliação, realizada em 1999, atribuiu nota 3, garantindo seu funcionamento e, já no ano seguinte, o programa alcançou nota 4, a máxima possível para programas restritos ao mestrado. Esse reconhecimento consolidou o Instituto como centro de formação de pesquisadores e impulsionou a ampliação da pós-graduação.',
        ],
        [
            'ano' => '2002',
            'titulo' => 'Recomendação do Doutorado pela CAPES',
            'texto' => 'Em 2002, a CAPES recomendou o Programa de Doutorado em Física, tornando-o o segundo do Centro de Tecnologia e Ciências (CTC) da UERJ e o primeiro localizado no campus Maracanã. O programa completo de pós-graduação, com mestrado e doutorado, iniciou suas atividades em março de 2003. Desde então, o programa vem recebendo turmas regulares, com crescente continuidade entre os níveis de formação. As primeiras defesas de tese ocorreram em 2007, marcando o amadurecimento do corpo docente e discente e a consolidação da pesquisa em Física na Universidade.',
        ],
        [
            'ano' => '2003',
            'titulo' => 'Ingresso dos Primeiros Cotistas',
            'texto' => 'Em 2003, a UERJ tornou-se a primeira universidade pública brasileira a adotar o sistema de cotas, e o curso de Física recebeu seus primeiros estudantes cotistas. Essa política pioneira representou um marco na democratização do acesso ao ensino superior e colaborou para transformar o perfil social do Instituto. O ingresso desses estudantes ampliou a diversidade na Universidade e reafirmou o compromisso da UERJ com a inclusão e a equidade.',
        ],
        [
            'ano' => '2004',
            'titulo' => 'Criação da Unidade HEPGrid',
            'texto' => 'Durante o evento LISHEP 2004, foi inaugurada a Unidade de Computação de Alto Desempenho (UCCAD-HEPGrid), a primeira unidade de grid do experimento CMS na América Latina. O projeto, conduzido pelo grupo de Física de Altas Energias, passou a integrar a rede internacional do CERN (Organização Europeia para a Pesquisa Nuclear), fornecendo suporte ao processamento e ao armazenamento de grandes volumes de dados científicos. A UCCAD-HEPGrid consolidou-se como um dos principais polos nacionais de infraestrutura computacional voltada à pesquisa em Física de Altas Energias.',
        ],
        [
            'ano' => '2006',
            'titulo' => 'Publicação na Nature Materials',
            'texto' => 'Em 2006, professores da área de Magnetismo do Instituto, em colaboração com o Instituto de Física Gleb Wataghin da UNICAMP, publicaram um artigo na Nature Materials apresentando uma alternativa ambientalmente eficiente à refrigeração convencional. O trabalho teve grande repercussão internacional e, em 2010, o grupo publicou um artigo de revisão que se tornou referência mundial na área de efeitos calóricos, reforçando a relevância da pesquisa desenvolvida no Instituto.',
        ],
        [
            'ano' => '2008',
            'titulo' => 'Formulação da Teoria RGZ',
            'texto' => 'Em 2008, docentes de Física Teórica do Instituto publicaram dois artigos que propuseram uma solução não perturbativa para o gluon, originando a teoria Refined Gribov-Zwanziger (RGZ). Essa formulação ganhou reconhecimento internacional e é estudada até hoje por diversos grupos de pesquisa teórica e de simulação em rede. O trabalho contribuiu para importantes avanços nas áreas teórica, computacional e fenomenológica, elevando o prestígio do Instituto no cenário científico global.',
        ],
        [
            'ano' => '2016',
            'titulo' => 'Consolidação do Regime de 40 Horas Docentes',
            'texto' => 'Em 2016, o Instituto de Física atingiu a marca de cerca de 95% de docentes com carga horária contratual de 40 horas semanais. No início da década de 1990, esse percentual era de aproximadamente 80%, alcançando 90% no final da década. Essa conquista resultou de políticas de fortalecimento da pesquisa, ampliação do quadro docente e consolidação da pós-graduação, o que levou à redução progressiva do número de professores com carga de 20 horas e à intensificação da dedicação à pesquisa científica na UERJ.',
        ],
        [
            'ano' => '2019',
            'titulo' => 'Qualificação do Corpo Docente',
            'texto' => 'Em 2019, o corpo docente do Instituto atingiu cerca de 95% de doutores, resultado direto das políticas de qualificação e incentivo à formação docente. Em 1992, o índice era de aproximadamente 25%, passando para 30% em 1994, 40% em 1995 e 75% em 2010. O aumento expressivo da titulação docente reflete a consolidação de uma cultura acadêmica voltada à pesquisa e o compromisso do Instituto com padrões internacionais de excelência científica e educacional.',
        ],
        [
            'ano' => '2022',
            'titulo' => 'Lançamento da revista Impacto',
            'texto' => 'Em 2022, foi lançada a revista Impacto: Pesquisa em Ensino de Ciências, o primeiro periódico científico do Instituto de Física. Voltada às áreas de Educação em Ciências, Ensino das Ciências da Natureza, História e Filosofia da Ciência e áreas afins, a revista nasceu com o propósito de divulgar estudos originais e promover o diálogo entre diferentes perspectivas sobre o ensino e a pesquisa em Ciências. A Impacto é um espaço de acolhimento de novas ideias e de fortalecimento da produção acadêmica no campo do ensino de Ciências.',
        ],
    ];

    ob_start();
    ?>
    <section class="fisica-timeline" id="<?php echo esc_attr( $timeline_id ); ?>" data-fisica-timeline aria-labelledby="<?php echo esc_attr( $timeline_id ); ?>-title">
        <?php if ( $show_header ) : ?>
            <div class="fisica-timeline__header">
                <span class="fisica-timeline__eyebrow">Linha do tempo</span>
                <h2 class="fisica-timeline__title" id="<?php echo esc_attr( $timeline_id ); ?>-title">A trajetória institucional do Instituto de Física da UERJ</h2>
                <p class="fisica-timeline__intro">Navegue pelos marcos históricos do Instituto e acompanhe, ano a ano, a consolidação de sua atuação em ensino, pesquisa e extensão.</p>
            </div>
        <?php else : ?>
            <h3 class="screen-reader-text" id="<?php echo esc_attr( $timeline_id ); ?>-title">Linha do tempo</h3>
        <?php endif; ?>

        <div class="fisica-timeline__layout">
            <div class="fisica-timeline__nav timeline-nav" role="tablist" aria-label="Anos da linha do tempo">
                <?php foreach ( $eventos as $index => $evento ) : ?>
                    <?php
                    $tab_id = $timeline_id . '-tab-' . $index;
                    $panel_id = $timeline_id . '-panel-' . $index;
                    $event_key = $timeline_id . '-item-' . $index;
                    ?>
                    <button
                        type="button"
                        class="fisica-timeline__year timeline-year<?php echo 0 === $index ? ' is-active' : ''; ?>"
                        id="<?php echo esc_attr( $tab_id ); ?>"
                        role="tab"
                        aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr( $panel_id ); ?>"
                        tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>"
                        data-fisica-timeline-tab
                        data-year="<?php echo esc_attr( $evento['ano'] ); ?>"
                        data-timeline-key="<?php echo esc_attr( $event_key ); ?>"
                        data-target="<?php echo esc_attr( $panel_id ); ?>"
                    >
                        <span class="fisica-timeline__year-number"><?php echo esc_html( $evento['ano'] ); ?></span>
                        <span class="fisica-timeline__year-label"><?php echo esc_html( $evento['titulo'] ); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="fisica-timeline__panels timeline-content-wrapper">
                <?php foreach ( $eventos as $index => $evento ) : ?>
                    <?php
                    $tab_id = $timeline_id . '-tab-' . $index;
                    $panel_id = $timeline_id . '-panel-' . $index;
                    $event_key = $timeline_id . '-item-' . $index;
                    ?>
                    <article
                        class="fisica-timeline__panel timeline-content<?php echo 0 === $index ? ' is-active' : ''; ?>"
                        id="<?php echo esc_attr( $panel_id ); ?>"
                        role="tabpanel"
                        aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
                        data-year="<?php echo esc_attr( $evento['ano'] ); ?>"
                        data-timeline-key="<?php echo esc_attr( $event_key ); ?>"
                        <?php echo 0 === $index ? '' : 'hidden'; ?>
                    >
                        <div class="fisica-timeline__panel-meta">
                            <span class="fisica-timeline__badge"><?php echo esc_html( $evento['ano'] ); ?></span>
                        </div>
                        <h3><?php echo esc_html( $evento['titulo'] ); ?></h3>
                        <p><?php echo esc_html( $evento['texto'] ); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php

    return ob_get_clean();
}
add_shortcode( 'fisica_linha_tempo_instituto', 'shortcode_fisica_linha_tempo_instituto' );
