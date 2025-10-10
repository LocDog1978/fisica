<?php
/**
 * Shortcode: [icones_servicos_uerj]
 * Descrição: Gera ícones animados circulares dos laboratórios.
 */

function shortcode_icones_servicos_uerj() {
    $servicos = [
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/Laboratorios-da-Fisica-03-12-2024-16.jpg',
            'desc' => 'Laboratório de Ensino de Física',
            'tooltip' => 'Conheça nosso Laboratório de Ensino de Fisica',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/Laboratorios-da-Fisica-05-12-2024-11.jpg',
            'desc' => 'Laboratório de Física e Partículas Nuclear',
            'tooltip' => 'Conheça Nosso Laboratório de Física e Partículas Nuclear',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_4052-2.jpg',
            'desc' => 'Laboratório de Física Moderna',
            'tooltip' => 'Conheça Nosso Laboratório de Física Moderna',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_2732-2.jpg',
            'desc' => 'Laboratório de Física Médica',
            'tooltip' => 'Conheça Nosso Laboratório de Física Médica',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_5776-2.jpg',
            'desc' => 'Laboratório HEPGrid (High Energy Physics Grid)',
            'tooltip' => 'Conheça Nosso Laboratório HEPGrid (High Energy Physics Grid)',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_2643-2.jpg',
            'desc' => 'Laboratório de Instrumentação Eletrônica e Técnicas Analíticas - LIETA',
            'tooltip' => 'Conheça Nosso Laboratório de Instrumentação Eletrônica e Técnicas Analíticas - LIETA',
            'link' => ''
        ],
        [
            'img' => 'http://localhost/fisica/wp-content/uploads/2025/09/GEO_4437-2.jpg',
            'desc' => 'Laboratório de Física e Eletricidade',
            'tooltip' => 'Conhaça Nosso Laboratório de Física e Eletricidade',
            'link' => ''
        ],
    ];

    ob_start(); ?>
    <div class="grid-linhas-servicos">
        <div class="linha-servicos"><?php for ($i = 0; $i < 4; $i++) echo icone_servico_html($servicos[$i]); ?></div>
        <div class="linha-servicos"><?php for ($i = 4; $i < 7; $i++) echo icone_servico_html($servicos[$i]); ?></div>
        
    </div>
    <?php return ob_get_clean();
}

function icone_servico_html($servico) {
    $img = esc_url($servico['img']);
    $desc = esc_html($servico['desc']);
    $tooltip = esc_attr($servico['tooltip'] ?? $desc);
    $link = esc_url($servico['link'] ?? '#');

    return "
    <div class='icone-servico' data-tooltip=\"{$tooltip}\">
        <a href=\"{$link}\" class=\"icone-link\">
            <div class='circulo-wrapper'>
                <div class='anel-fixo'></div>
                <div class='anel-animado'></div>
                <div class='circulo-interno' style='background-image: url({$img});'></div>
            </div>
        </a>
        <p class='descricao-servico'>{$desc}</p>
    </div>
    ";
}
add_shortcode('icones_servicos_uerj', 'shortcode_icones_servicos_uerj');

function estilos_icones_servicos_uerj() {
    echo '
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .grid-linhas-servicos {
            display: flex;
            flex-direction: column;
            gap: 100px;
            max-width: 1000px;
            margin: 50px auto;
            font-family: "Poppins", sans-serif;
        }

        .linha-servicos {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 50px;
        }

        .linha-servicos.unica-centralizada {
            justify-content: center;
        }

        .icone-servico {
            text-align: center;
            width: 200px;
            position: relative;
        }

        .icone-link {
            text-decoration: none;
            display: block;
        }

        .icone-servico .descricao-servico {
            text-decoration: none;
            margin-top: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #003366;
        }

        .icone-link:hover + .descricao-servico,
        .icone-servico:hover .descricao-servico {
            text-decoration: underline;
        }

        .circulo-wrapper {
            position: relative;
            width: 200px;
            height: 200px;
        }

        .anel-fixo {
            width: 200px;
            height: 200px;
            border: 20px solid #66aaff;
            border-radius: 50%;
            box-sizing: border-box;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .anel-animado {
            width: 200px;
            height: 200px;
            border: 20px solid transparent;
            border-top: 20px solid #003366;
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            opacity: 0;
            pointer-events: none;
            box-sizing: border-box;
            clip-path: inset(0px round 50%);
        }

        .circulo-wrapper:hover .anel-animado {
            animation: girar-circulo 3s linear infinite;
            opacity: 1;
        }

        .circulo-interno {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 3;
        }

        @keyframes girar-circulo {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .icone-servico::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 230px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
            opacity: 0;
            pointer-events: none;
            white-space: nowrap;
            transition: opacity 0.3s ease;
            z-index: 99;
        }

        .icone-servico:hover::after {
            opacity: 1;
        }

        @media (max-width: 1024px) {
            .linha-servicos {
                gap: 30px;
            }
        }

        @media (max-width: 768px) {
            .linha-servicos {
                justify-content: center;
            }
            .icone-servico {
                width: 160px;
            }
            .circulo-wrapper,
            .anel-fixo,
            .anel-animado {
                width: 160px;
                height: 160px;
            }
            .circulo-interno {
                width: 120px;
                height: 120px;
                top: 20px;
                left: 20px;
            }
        }

        @media (max-width: 480px) {
            .linha-servicos {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
    ';
}
add_action('wp_footer', 'estilos_icones_servicos_uerj');
