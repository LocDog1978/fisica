<?php
/**
 * Shortcode: [fisica_sobre_o_instituto]
 * Description: Renderiza a página Sobre o Instituto com a estrutura definida.
 */

function shortcode_fisica_sobre_o_instituto() {
    $diretor_image_url = fisica_site_url( '/wp-content/uploads/2026/04/Gerson-Pech.jpg' );
    $vice_image_url    = fisica_site_url( '/wp-content/uploads/2026/04/Marcelo-Chiapparini.jpg' );

    ob_start();
    ?>
    <article class="fisica-sobre-instituto">
        <div class="fisica-sobre-instituto__hero">
            <span class="fisica-sobre-instituto__tab" aria-label="Seção atual">O Instituto</span>
        </div>

        <div class="fisica-sobre-instituto__content">
            <section class="fisica-sobre-instituto__section" aria-labelledby="conheca-o-instituto">
                <h2 id="conheca-o-instituto">Conheça o Instituto</h2>
                <p>O Instituto de Física da Universidade do Estado do Rio de Janeiro é uma unidade acadêmica com trajetória marcada pela inovação, pela autonomia institucional e pelo compromisso com a excelência científica e a inclusão social. Desde suas origens, na década de 1940, em um ambiente educacional progressista, até sua consolidação no campus Maracanã, o Instituto desenvolveu um perfil que integra ensino, pesquisa e extensão, com atuação em áreas que vão da física teórica fundamental às aplicações e ao ensino de Ciências. Ao longo do tempo, estruturou-se com departamentos especializados, consolidou programas de pós-graduação reconhecidos, ampliou sua produção científica com impacto nacional e internacional e incorporou políticas pioneiras de democratização do acesso. No presente, destaca-se pela qualificação de seu corpo docente, pela infraestrutura de pesquisa e pela inserção em redes científicas globais. Para o futuro, a tendência é o fortalecimento da interdisciplinaridade, da internacionalização e da inovação científica e educacional, mantendo seu compromisso histórico com a formação de professores e pesquisadores, a produção de conhecimento e a transformação social.</p>
            </section>

            <section class="fisica-sobre-instituto__section" aria-labelledby="direcao">
                <h2 id="direcao">Direção</h2>
                <p>A Direção do Instituto de Física da Universidade do Estado do Rio de Janeiro é o órgão responsável pela gestão acadêmica e administrativa da unidade, coordenando o planejamento estratégico e a execução das atividades de ensino, pesquisa e extensão. Cabe à Direção garantir o bom funcionamento dos cursos de graduação e pós-graduação, promover a integração entre docentes, discentes e técnicos, representar o Instituto junto à administração central e a outras instituições, além de zelar pelo cumprimento das normas institucionais. A Direção preside o Conselho Departamental, instância máxima deliberativa da unidade, conduzindo suas reuniões e garantindo a implementação de suas decisões. Em suma, sua função é assegurar a qualidade acadêmica, o desenvolvimento científico e a organização eficiente do Instituto de Física.</p>

                <div class="direcao-perfis">
                    <section class="direcao-card" aria-labelledby="diretor">
                        <div class="direcao-foto">
                            <img src="<?php echo esc_url( $diretor_image_url ); ?>" alt="Diretor - Gerson Pech">
                        </div>
                        <div class="direcao-conteudo">
                            <h3 id="diretor">Diretor</h3>
                            <p><strong>Gerson Pech</strong></p>
                            <p>Professor associado do Departamento de Física Nuclear e Altas Energias (DFNAE), na Universidade do Estado do Rio de Janeiro, atua como pesquisador nas áreas de ciência da informação e cienciometria, desenvolvendo estudos sobre produção científica e análise de dados acadêmicos. É bacharel em Física pela Universidade Federal do Rio de Janeiro (1986) e possui mestrado em Astrofísica e doutorado em Física de Partículas pelo Centro Brasileiro de Pesquisas Físicas. Realizou pós-doutorado em Ciência da Informação na Universidade do Porto e possui especialização em Gestão de Projetos pela Fundação Getulio Vargas. Integra colegiados institucionais da UERJ, como a Comissão Permanente de Graduação (CPG) e o Conselho Superior de Ensino, Pesquisa e Extensão (CSEPE).</p>
                        </div>
                    </section>

                    <section class="direcao-card" aria-labelledby="vice-diretor">
                        <div class="direcao-foto">
                            <img src="<?php echo esc_url( $vice_image_url ); ?>" alt="Vice-diretor - Marcelo Chiapparini">
                        </div>
                        <div class="direcao-conteudo">
                            <h3 id="vice-diretor">Vice-diretor</h3>
                            <p><strong>Marcelo Chiapparini</strong></p>
                            <p>Professor associado do Departamento de Física Teórica (DFT) da Universidade do Estado do Rio de Janeiro, atua como pesquisador nas áreas de física hadrônica e astrofísica nuclear. É bacharel e doutor em Ciências Físicas pela Universidade de Buenos Aires, tendo também atuado como pesquisador na Comisión Nacional de Energía Atómica. Realizou pós-doutorado em física hadrônica no Centro Brasileiro de Pesquisas Físicas. Foi coordenador do Programa de Pós-Graduação em Física do Instituto de Física por quatro anos, período em que o curso de doutorado obteve recomendação da Coordenação de Aperfeiçoamento de Pessoal de Nível Superior, consolidando-se institucionalmente.</p>
                        </div>
                    </section>
                </div>
            </section>

            <section class="fisica-sobre-instituto__section" aria-labelledby="linha-do-tempo">
                <h2 id="linha-do-tempo">Linha do tempo</h2>
                <?php echo do_shortcode( '[fisica_linha_tempo_instituto show_header="false"]' ); ?>
            </section>

            <section class="fisica-sobre-instituto__section" aria-labelledby="plano-de-desenvolvimento-do-instituto">
                <h2 id="plano-de-desenvolvimento-do-instituto">Plano de Desenvolvimento do Instituto</h2>

                <section class="fisica-sobre-instituto__subsection" aria-labelledby="missao">
                    <h3 id="missao">Missão</h3>
                    <p>Gerar e difundir conhecimento em Física, em seu ensino e em tecnologias associadas, integrando excelência no ensino, na pesquisa e na extensão, atuando com responsabilidade social e formando profissionais qualificados e comprometidos com a sociedade.</p>
                </section>

                <section class="fisica-sobre-instituto__subsection" aria-labelledby="valores">
                    <h3 id="valores">Valores</h3>
                    <ul class="fisica-sobre-instituto__values" aria-label="Valores do Instituto de Física">
                        <li>Criatividade</li>
                        <li>Inovação</li>
                        <li>Diversidade</li>
                        <li>Sustentabilidade</li>
                        <li>Integração</li>
                        <li>Responsabilidade social</li>
                        <li>Excelência</li>
                        <li>Inclusão</li>
                        <li>Ética</li>
                        <li>Qualidade</li>
                        <li>Informação</li>
                        <li>Compromisso</li>
                    </ul>
                </section>

                <section class="fisica-sobre-instituto__subsection" aria-labelledby="macro-objetivos-do-instituto-de-fisica">
                    <h3 id="macro-objetivos-do-instituto-de-fisica">Macro-objetivos do Instituto de Física</h3>
                    <ol class="fisica-sobre-instituto__objectives">
                        <li>Produzir, preservar e difundir o conhecimento científico e tecnológico.</li>
                        <li>Promover a excelência acadêmica.</li>
                        <li>Promover a permanência e o êxito estudantil.</li>
                        <li>Atualizar e expandir a infraestrutura física, assegurando sua adequação às necessidades acadêmicas, administrativas e comunitárias do Instituto.</li>
                        <li>Intensificar a articulação regional, nacional e internacional, o intercâmbio de conhecimento e a cooperação técnica, científica, extensionista e cultural.</li>
                        <li>Promover a interdisciplinaridade da Física com outras áreas do conhecimento.</li>
                        <li>Fomentar a democracia, a justiça social, a laicidade, a transparência, a ética e os direitos humanos.</li>
                        <li>Estimular a integração e a valorização dos corpos discente, técnico e docente do Instituto.</li>
                        <li>Estimular a acessibilidade, a inclusão social e etária, a equidade de gênero, étnico-racial e a diversidade cultural.</li>
                        <li>Estimular a integração dos departamentos e das coordenações acadêmicas do Instituto de Física.</li>
                        <li>Aprimorar os mecanismos de avaliação da unidade como instrumento de suporte à tomada de decisão, promovendo melhorias acadêmicas e de gestão.</li>
                        <li>Promover atenção à saúde da comunidade universitária do Instituto.</li>
                    </ol>
                </section>
            </section>
        </div>
    </article>
    <?php

    return ob_get_clean();
}
add_shortcode( 'fisica_sobre_o_instituto', 'shortcode_fisica_sobre_o_instituto' );

if ( ! function_exists( 'fisica_render_sobre_o_instituto_page' ) ) {
    /**
     * Replace the existing page body with the institutional layout.
     *
     * @param string $content Current page content.
     *
     * @return string
     */
    function fisica_render_sobre_o_instituto_page( $content ) {
        if ( is_admin() || ! is_singular( 'page' ) || 297 !== (int) get_queried_object_id() ) {
            return $content;
        }

        return do_shortcode( '[fisica_sobre_o_instituto]' );
    }
}
add_filter( 'the_content', 'fisica_render_sobre_o_instituto_page', 20 );
