<?php
/**
 * Shortcode: [fisica_linha_tempo_instituto]
 * Description: Exibe a linha do tempo do Instituto de Fisica da UERJ.
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
            'titulo' => 'Reconhecimento do Curso de Fisica',
            'texto' => 'O curso de Fisica foi oficialmente reconhecido em 1944, quando ainda funcionava no antigo Instituto La-Fayette, situado na Rua Haddock Lobo n° 253, na Tijuca. O Instituto La-Fayette foi um colegio inovador e progressista para sua epoca, pioneiro na implantacao do jardim de infancia e em iniciativas de inclusao social e educacional, como o acolhimento de filhos de pais desquitados, o ecumenismo e a recepcao de alunos negros em seu internato. Nesse ambiente de vanguarda, a formacao em Fisica comecou a ganhar contornos institucionais, lancando as bases do que viria a ser o futuro Instituto de Fisica da UERJ.',
        ],
        [
            'ano' => '1954',
            'titulo' => 'Integracao a Faculdade de Filosofia, Ciencias e Letras',
            'texto' => 'Em 1954, o Instituto de Fisica foi incorporado a Faculdade de Filosofia, Ciencias e Letras (FFCL) da Universidade do Distrito Federal (UDF), denominacao inicial da atual UERJ. A UDF havia sido formada pela uniao de quatro faculdades - Ciencias Economicas, Direito, Ciencias Medicas e a propria FFCL, derivada da Faculdade de Filosofia do Instituto La-Fayette. Essa integracao ampliou as possibilidades de pesquisa e a formacao de professores e pesquisadores em um ambiente academico mais estruturado.',
        ],
        [
            'ano' => '1966',
            'titulo' => 'Criacao dos Institutos Basicos da Universidade',
            'texto' => 'Em 1966, foram criados os Institutos Basicos da UERJ, consolidando as atividades de ensino em areas fundamentais, entre elas a Fisica. A reorganizacao foi formalizada pela Resolucao n° 296, de 14 de outubro de 1966, que instituiu os Institutos de Fisica, Quimica, Matematica e Estatistica, Desenho e Artes Aplicadas e Geociencias, alinhada com o Plano de Reestruturacao da antiga UEG. Diferentemente dos demais, o Instituto de Fisica ja havia se desvinculado da Faculdade de Filosofia, Ciencias e Letras, o que lhe conferiu autonomia precoce. Essa separacao foi decisiva para seu fortalecimento institucional, permitindo-lhe assumir um papel de destaque na nova estrutura universitaria.',
        ],
        [
            'ano' => '1974',
            'titulo' => 'Instalacao no Pavilhao Joao Lyra Filho',
            'texto' => 'Em 1974, o Instituto de Fisica foi transferido para o terceiro andar do Pavilhao Joao Lyra Filho, no campus Maracana, marcando uma nova fase de consolidacao fisica e academica. Nesse espaco, onde permanece ate hoje, foram instaladas salas de professores, laboratorios de ensino e pesquisa, biblioteca, salas de aula, o Programa de Pos-Graduacao, o CAFIS e auditorios. A convivencia cotidiana entre estudantes, docentes e tecnicos nesse ambiente dinamico favoreceu e favorece a troca de ideias, a formacao cientifica e o fortalecimento da integracao das atividades de ensino, pesquisa e extensao.',
        ],
        [
            'ano' => '1983',
            'titulo' => 'Primeira Semana de Fisica da UERJ',
            'texto' => 'A realizacao da primeira Semana de Fisica da UERJ, em 1983, representou um marco na integracao entre ensino, pesquisa e extensao. Organizada pelo Centro Academico de Fisica (CAFIS), a Semana de Fisica se consolidou como evento anual, reunindo palestras, minicursos e atividades voltadas a divulgacao cientifica e a interacao entre estudantes, professores e pesquisadores de diferentes areas e instituicoes. A iniciativa, que permanece ate hoje - em 2025, na 23ª edicao -, ajudou a fortalecer o sentimento de pertencimento cientifico e a visibilidade do Instituto.',
        ],
        [
            'ano' => '1991',
            'titulo' => 'Reestruturacao dos Departamentos',
            'texto' => 'Em 1991, o Instituto de Fisica passou por uma importante reestruturacao departamental, que definiu uma nova organizacao interna: Departamento de Fisica Teorica (DFT), Departamento de Fisica Aplicada e Termodinamica (DFAT), Departamento de Eletronica Quantica (DEQ) e Departamento de Fisica Nuclear e Altas Energias (DFNAE). Essa reorganizacao aprimorou a gestao academica e cientifica, distribuindo as atividades de ensino e as areas de atuacao de forma mais eficaz, o que contribuiu para o desenvolvimento de grupos de pesquisa mais consolidados e especializados, incluindo a pesquisa do ensino em Fisica.',
        ],
        [
            'ano' => '1991',
            'titulo' => 'Primeira Reformulacao do Curso de Fisica',
            'texto' => 'No mesmo ano, foi realizada a primeira grande reformulacao do curso de Fisica, um passo decisivo na consolidacao do Instituto como espaco de formacao profissional. O novo curriculo foi planejado para aproximar o ensino das atividades de pesquisa, incentivando a participacao dos estudantes em projetos extracurriculares desde as etapas iniciais da graduacao. Essa reformulacao marcou o inicio de uma cultura academica voltada a producao de conhecimento e a formacao de pesquisadores.',
        ],
        [
            'ano' => '1995',
            'titulo' => 'Criacao da Biblioteca do Instituto de Fisica',
            'texto' => 'A Biblioteca do Instituto de Fisica foi inaugurada em 1995, no terceiro andar do Pavilhao Joao Lyra Filho, com o acervo da area desmembrado da Biblioteca CTC/A. O acervo inicial foi enriquecido por doacoes do Clube de Leitura do Instituto de Fisica (CLIF) e por bibliotecas particulares de docentes. Logo apos sua criacao, a biblioteca recebeu seus primeiros computadores e passou a oferecer, em 1997, a base de dados BIB (MicroIsis) para consulta eletronica. Foi tambem uma das primeiras da UERJ a climatizar o seu espaco, gracas a atuacao da Comissao de Biblioteca, tornando-se um ambiente de referencia para os estudantes.',
        ],
        [
            'ano' => '1997',
            'titulo' => 'Recomendacao do Mestrado pela CAPES',
            'texto' => 'Em 1997, a CAPES recomendou o Programa de Mestrado do Instituto de Fisica da UERJ, reconhecendo a maturidade academica alcancada. A primeira avaliacao, realizada em 1999, atribuiu nota 3, garantindo seu funcionamento, e, ja no ano seguinte, o programa alcancou nota 4 - a maxima possivel para programas restritos ao Mestrado. Esse reconhecimento consolidou o Instituto como centro de formacao de pesquisadores e impulsionou a ampliacao da Pos-Graduacao.',
        ],
        [
            'ano' => '2002',
            'titulo' => 'Recomendacao do Doutorado pela CAPES',
            'texto' => 'Em 2002, a CAPES recomendou o Programa de Doutorado em Fisica, tornando-o o segundo do Centro de Tecnologia e Ciencias (CTC) da UERJ e o primeiro localizado no campus Maracana. O programa completo de Pos-Graduacao, com Mestrado e Doutorado, iniciou suas atividades em marco de 2003. Desde entao, o programa vem recebendo turmas regulares, com crescente continuidade entre os niveis de formacao. As primeiras defesas de tese ocorreram em 2007, marcando o amadurecimento do corpo docente e discente e a consolidacao da pesquisa em Fisica na universidade.',
        ],
        [
            'ano' => '2003',
            'titulo' => 'Ingresso dos Primeiros Cotistas',
            'texto' => 'Em 2003, a UERJ tornou-se a primeira universidade publica brasileira a adotar o sistema de cotas, e o curso de Fisica recebeu seus primeiros estudantes cotistas. Essa politica pioneira representou um marco na democratizacao do acesso ao ensino superior e colaborou para transformar o perfil social do Instituto. O ingresso desses estudantes ampliou a diversidade na universidade e reafirmou o compromisso da UERJ com a inclusao e a equidade.',
        ],
        [
            'ano' => '2004',
            'titulo' => 'Criacao da Unidade HEPGrid',
            'texto' => 'Durante o evento LISHEP 2004, foi inaugurada a Unidade de Computacao de Alto Desempenho (UCCAD-HEPGrid), a primeira unidade de grid do experimento CMS na America Latina. O projeto, conduzido pelo grupo de Fisica de Altas Energias, passou a integrar a rede internacional do CERN (Organizacao Europeia para a Pesquisa Nuclear), fornecendo suporte ao processamento e armazenamento de grandes volumes de dados cientificos. A UCCAD-HEPGrid consolidou-se como um dos principais polos nacionais de infraestrutura computacional voltada a pesquisa em Fisica de Altas Energias.',
        ],
        [
            'ano' => '2006',
            'titulo' => 'Publicacao na Nature Materials',
            'texto' => 'Em 2006, professores da area de Magnetismo do Instituto, em colaboracao com o Instituto de Fisica Gleb Wataghin da UNICAMP, publicaram um artigo na Nature Materials apresentando uma alternativa ambientalmente eficiente a refrigeracao convencional. O trabalho teve grande repercussao internacional e, em 2010, o grupo publicou um artigo de revisao que se tornou referencia mundial na area de efeitos caloricos, reforcando a relevancia da pesquisa desenvolvida no Instituto.',
        ],
        [
            'ano' => '2008',
            'titulo' => 'Formulação da teoria RGZ',
            'texto' => 'Em 2008, docentes de Fisica Teorica do Instituto publicaram dois artigos que propuseram uma solucao nao perturbativa para o gluon, originando a teoria Refined Gribov-Zwanziger (RGZ). Essa formulacao ganhou reconhecimento internacional e e estudada ate hoje por diversos grupos de pesquisa teorica e de simulacao em rede. O trabalho contribuiu para importantes avancos nas areas teorica, computacional e fenomenologica, elevando o prestigio do Instituto no cenario cientifico global.',
        ],
        [
            'ano' => '2016',
            'titulo' => 'Consolidacao do Regime de 40 Horas Docentes',
            'texto' => 'Em 2016, o Instituto de Fisica atingiu a marca de cerca de 95% de docentes com carga horaria contratual de 40 horas semanais. No inicio da decada de 1990, esse percentual era de aproximadamente 80%, alcancando 90% no final da decada. Essa conquista resultou de politicas de fortalecimento da pesquisa, ampliacao do quadro docente e consolidacao da Pos-Graduacao, o que levou a reducao progressiva do numero de professores com carga de 20 horas e a intensificacao da dedicacao a pesquisa cientifica na UERJ.',
        ],
        [
            'ano' => '2019',
            'titulo' => 'Qualificacao do Corpo Docente',
            'texto' => 'Em 2019, o corpo docente do Instituto atingiu cerca de 95% de doutores, resultado direto das politicas de qualificacao e incentivo a formacao docente. Em 1992, o indice era de aproximadamente 25%, passando para 30% em 1994, 40% em 1995 e 75% em 2010. O aumento expressivo da titulacao docente reflete a consolidacao de uma cultura academica voltada a pesquisa e o compromisso do Instituto com padroes internacionais de excelencia cientifica e educacional.',
        ],
        [
            'ano' => '2022',
            'titulo' => 'Lancamento da revista Impacto',
            'texto' => 'Em 2022, foi lancada a revista Impacto: Pesquisa em Ensino de Ciencias, o primeiro periodico cientifico do Instituto de Fisica. Voltada as areas de Educacao em Ciencias, Ensino das Ciencias da Natureza, Historia e Filosofia da Ciencia e areas afins, a revista nasceu com o proposito de divulgar estudos originais e promover o dialogo entre diferentes perspectivas sobre o ensino e a pesquisa em Ciencias. A Impacto e um espaco de acolhimento de novas ideias e de fortalecimento da producao academica no campo do ensino de Ciencias.',
        ],
    ];

    ob_start();
    ?>
    <section class="fisica-timeline" id="<?php echo esc_attr( $timeline_id ); ?>" data-fisica-timeline aria-labelledby="<?php echo esc_attr( $timeline_id ); ?>-title">
        <?php if ( $show_header ) : ?>
            <div class="fisica-timeline__header">
                <span class="fisica-timeline__eyebrow">Linha do Tempo</span>
                <h2 class="fisica-timeline__title" id="<?php echo esc_attr( $timeline_id ); ?>-title">A trajetoria institucional do Instituto de Fisica da UERJ</h2>
                <p class="fisica-timeline__intro">Navegue pelos marcos historicos do Instituto e acompanhe, ano a ano, a consolidacao de sua atuacao em ensino, pesquisa e extensao. Texto-base em versao de discussao.</p>
            </div>
        <?php else : ?>
            <h3 class="screen-reader-text" id="<?php echo esc_attr( $timeline_id ); ?>-title">Linha do Tempo</h3>
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
