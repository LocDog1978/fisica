<?php
/**
 * Shortcode: [quadrados_servicos_link]
 * Descrição: Gera quadrados clicáveis que redirecionam para outras páginas.
 */

function shortcode_quadrados_servicos_link() {
    // 🔹 Defina aqui os títulos, descrições e links de destino
    $servicos = [
        [
            'titulo' => 'Física Aplicada',
            'descricao' => 'às Ciências Biomédicas e Ambientais',
            'link' => 'http://localhost/fisica/index.php/ciencias-biomedicas-ambientais/'
        ],
        [
            'titulo' => 'Aplicações Industriais',
            'descricao' => 'de Radioisótopos',
            'link' => 'http://localhost/fisica/index.php/aplicacoes-industriais/'
        ],
        [
            'titulo' => 'Ensino de Física',
            'descricao' => 'Formação de Professores ',
            'link' => 'http://localhost/fisica/index.php/ensino-de-fisico/'
        ],
        [
            'titulo' => 'Física Nuclear ',
            'descricao' => 'Aplicada',
            'link' => 'http://localhost/fisica/index.php/nuclear-aplicada/'
        ],
        [
            'titulo' => 'Física da Matéria',
            'descricao' => 'Condensada',
            'link' => 'http://localhost/fisica/index.php/materia-condensada/'
        ],
        [
            'titulo' => 'Magnetismo',
            'descricao' => 'e Materiais Magnéticos',
            'link' => 'http://localhost/fisica/index.php/magnetismo/'
        ],
        [
            'titulo' => 'Sensores',
            'descricao' => 'e Fibra Óticas',
            'link' => 'http://localhost/fisica/index.php/sensores-e-fibras-oticas/'
        ],
        [
            'titulo' => 'Teoria Quântica',
            'descricao' => 'de Campos',
            'link' => 'http://localhost/fisica/index.php/teoria-quantica/'
        ],
        [
            'titulo' => 'Gravitação',
            'descricao' => 'e Cosmologia',
            'link' => 'http://localhost/fisica/index.php/gravitacao/'
        ],
         [
            'titulo' => 'Física',
            'descricao' => 'Matemática',
            'link' => 'http://localhost/fisica/index.php/matematica/'
        ],
         [
            'titulo' => 'Física Experimental',
            'descricao' => 'de Altas Energias',
            'link' => 'http://localhost/fisica/index.php/altas-energias/'
        ],
    
    ];

    ob_start(); ?>

    <div class="grid-quadrados-servicos">
        <?php foreach (array_chunk($servicos, 3) as $linha): ?>
            <div class="linha-quadrados">
                <?php foreach ($linha as $s): ?>
                    <?php echo quadrado_servico_link_html($s['titulo'], $s['descricao'], $s['link']); ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('quadrados_servicos_link', 'shortcode_quadrados_servicos_link');

// 🔹 Função que gera o HTML de cada quadrado com link
function quadrado_servico_link_html($titulo, $descricao, $link) {
    $titulo_esc = esc_html($titulo);
    $descricao_esc = esc_html($descricao);
    $link_esc = esc_url($link);

    return "
    <a href='{$link_esc}' class='quadrado-servico'>
        <div class='quadrado-conteudo'>
            <h3>{$titulo_esc}</h3>
            <p>{$descricao_esc}</p>
        </div>
    </a>";
}

// 🔹 Estilos do shortcode (sem modal e sem JS)
function estilos_scripts_quadrados_servicos_link() {
    echo '
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .grid-quadrados-servicos {
            display: flex;
            flex-direction: column;
            gap: 30px;
            max-width: 1000px;
            margin: 50px auto;
            font-family: "Poppins", sans-serif;
        }

        .linha-quadrados {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .quadrado-servico {
            background: white;
            border: 3px solid #66aaff;
            border-radius: 12px;
            padding: 20px;
            width: 300px;
            min-height: 180px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 51, 102, 0.1);
            position: relative;
            overflow: hidden;
        }

        .quadrado-servico::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 170, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .quadrado-servico:hover::before {
            left: 100%;
        }

        .quadrado-servico:hover {
            transform: translateY(-5px);
            border-color: #003366;
            box-shadow: 0 8px 25px rgba(0, 51, 102, 0.15);
        }

        .quadrado-conteudo {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .quadrado-servico h3 {
            color: #003366;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .quadrado-servico p {
            color: #666;
            font-size: 14px;
            line-height: 1.4;
        }

        @media (max-width: 1024px) {
            .linha-quadrados {
                gap: 15px;
            }
            .quadrado-servico {
                width: 280px;
            }
        }

        @media (max-width: 900px) {
            .quadrado-servico {
                width: 100%;
                max-width: 300px;
            }
        }

        @media (max-width: 768px) {
            .linha-quadrados {
                flex-direction: column;
                align-items: center;
            }
            .quadrado-servico {
                min-height: 160px;
                padding: 16px;
            }
            .quadrado-servico h3 {
                font-size: 16px;
            }
            .quadrado-servico p {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .grid-quadrados-servicos {
                margin: 30px auto;
            }
            .quadrado-servico {
                min-height: 140px;
            }
        }
    </style>';
}
add_action('wp_footer', 'estilos_scripts_quadrados_servicos_link');
