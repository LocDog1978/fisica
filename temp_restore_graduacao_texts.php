<?php
$mysqli = new mysqli('localhost', 'root', '', 'fisica');

if ($mysqli->connect_error) {
    fwrite(STDERR, "DB connection failed: {$mysqli->connect_error}\n");
    exit(1);
}

$mysqli->set_charset('utf8mb4');

$post_id = 1094;
$meta = $mysqli->query("SELECT meta_id FROM wp_postmeta WHERE post_id = {$post_id} AND meta_key = '_elementor_data' ORDER BY meta_id DESC LIMIT 1")->fetch_row();

if (!$meta) {
    fwrite(STDERR, "Meta _elementor_data não encontrada.\n");
    exit(1);
}

$meta_id = (int) $meta[0];

$html = <<<'HTML'
<section class="fisica-graduacao-page">
  <header class="fisica-graduacao-hero">
    <span class="fisica-graduacao-eyebrow">Instituto de Física UERJ</span>
    <h1>Graduação</h1>
    <p>Apresentação institucional do curso de Física do Instituto de Física “Armando Dias Tavares” — IFADT/UERJ, nas modalidades Bacharelado e Licenciatura.</p>
  </header>

  <section class="fisica-graduacao-section" aria-label="Modalidades do curso">
    <div class="fisica-graduacao-section__head">
      <span class="fisica-graduacao-section__eyebrow">Modalidades</span>
      <h2>Bacharelado e Licenciatura</h2>
    </div>
    <div class="fisica-graduacao-section__body">
      <div class="fisica-graduacao-modalidades">
        <article class="fisica-graduacao-card">
          <h3>Apresentação</h3>
          <p>O ingresso no curso ocorre por meio do vestibular, com entrada no primeiro semestre de cada ano no turno Manhã/Tarde e no segundo semestre no turno Tarde/Noite.</p>
          <p>O curso encontra-se em sua 7ª versão e passa por constante análise, buscando atender às normas vigentes e às demandas da sociedade.</p>
        </article>
        <article class="fisica-graduacao-card">
          <h3>Estrutura e Formação</h3>
          <p>O Instituto dispõe de biblioteca voltada para a graduação, com espaço de estudo para os estudantes.</p>
          <p>Anualmente, são oferecidas oportunidades em projetos de Iniciação Científica, Estágios, Iniciação à Docência e Extensão, visando aprimorar a formação acadêmica e fortalecer a interação entre discentes e docentes da Universidade.</p>
          <p>O curso de Física prepara o estudante para atuar em pesquisa, ensino e em áreas que exigem sólida formação em Física.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="fisica-graduacao-grid" aria-label="Informações administrativas e acadêmicas">
    <article class="fisica-graduacao-section">
      <div class="fisica-graduacao-section__head">
        <span class="fisica-graduacao-section__eyebrow">Informações administrativas</span>
        <h2>Contato e atendimento</h2>
      </div>
      <div class="fisica-graduacao-section__body">
        <div class="fisica-graduacao-info-list">
          <div class="fisica-graduacao-info-item">
            <strong>Local</strong>
            <span>3º andar do Pavilhão João Lyra Filho — Campus Maracanã</span>
          </div>
          <div class="fisica-graduacao-info-item">
            <strong>Telefone</strong>
            <span>(21) 2334-0045</span>
          </div>
          <div class="fisica-graduacao-info-item">
            <strong>E-mail</strong>
            <a href="mailto:secretaria.if@uerj.br">secretaria.if@uerj.br</a>
          </div>
          <div class="fisica-graduacao-info-item">
            <strong>Horário de atendimento</strong>
            <span>00:00 às 00:00</span>
          </div>
        </div>
      </div>
    </article>

    <article class="fisica-graduacao-section">
      <div class="fisica-graduacao-section__head">
        <span class="fisica-graduacao-section__eyebrow">Informações acadêmicas</span>
        <h2>Referências de consulta</h2>
      </div>
      <div class="fisica-graduacao-section__body">
        <div class="fisica-graduacao-info-list">
          <div class="fisica-graduacao-info-item">
            <strong>Fluxograma</strong>
            <span>Informação acadêmica da graduação.</span>
          </div>
          <div class="fisica-graduacao-info-item">
            <strong>Ementa</strong>
            <span>Referência institucional do curso.</span>
          </div>
          <div class="fisica-graduacao-info-item">
            <strong>Orientações acadêmicas</strong>
            <span>Informativo, quadro de horários, salas e demais orientações acadêmicas.</span>
          </div>
        </div>
      </div>
    </article>
  </section>
</section>
HTML;

$data = [
    [
        'id' => 'graduacaohtmlv1',
        'elType' => 'container',
        'settings' => [
            'content_width' => 'full',
            'flex_direction' => 'column',
            'padding' => [
                'unit' => 'px',
                'top' => '0',
                'right' => '0',
                'bottom' => '0',
                'left' => '0',
                'isLinked' => false,
            ],
        ],
        'elements' => [
            [
                'id' => 'graduacaohtmlwidgetv1',
                'elType' => 'widget',
                'widgetType' => 'html',
                'settings' => [
                    'html' => $html,
                ],
                'elements' => [],
            ],
        ],
    ],
];

$json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$stmt = $mysqli->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?");
$stmt->bind_param('si', $json, $meta_id);
$stmt->execute();
$stmt->close();

echo "Textos restaurados.\n";
