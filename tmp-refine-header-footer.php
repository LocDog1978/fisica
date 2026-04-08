<?php
$mysqli = new mysqli('localhost', 'root', '', 'fisica');
if ($mysqli->connect_error) {
    fwrite(STDERR, "DB connection failed: {$mysqli->connect_error}\n");
    exit(1);
}
$mysqli->set_charset('utf8mb4');

function update_meta(mysqli $mysqli, int $postId, string $key, string $value): void {
    $stmt = $mysqli->prepare("SELECT meta_id FROM wp_postmeta WHERE post_id = ? AND meta_key = ? ORDER BY meta_id DESC LIMIT 1");
    $stmt->bind_param('is', $postId, $key);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($row) {
        $metaId = (int) $row['meta_id'];
        $stmt = $mysqli->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?");
        $stmt->bind_param('si', $value, $metaId);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $mysqli->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $postId, $key, $value);
        $stmt->execute();
        $stmt->close();
    }
}

function backup_elementor_data(mysqli $mysqli, int $postId, string $name): void {
    $stmt = $mysqli->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data' ORDER BY meta_id DESC LIMIT 1");
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_row();
    $stmt->close();
    $dir = 'c:/xampp/htdocs/fisica/wp-content/uploads/elementor-db-backups';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($dir . '/header-footer-refine-' . $name . '-' . date('Ymd-His') . '.json', $row[0] ?? '[]');
}

$headerData = [
    [
        'id' => 'fisheader2',
        'elType' => 'container',
        'settings' => [
            '_css_classes' => 'fisica-site-header',
            'flex_direction' => 'row',
            'content_width' => 'boxed',
            'boxed_width' => ['unit' => 'px', 'size' => 1284, 'sizes' => []],
            'min_height' => ['unit' => 'px', 'size' => 94, 'sizes' => []],
            'flex_justify_content' => 'space-between',
            'flex_align_items' => 'center',
            'padding' => ['unit' => 'px', 'top' => '12', 'right' => '20', 'bottom' => '12', 'left' => '20', 'isLinked' => false],
        ],
        'elements' => [
            [
                'id' => 'fisheaderlogo2',
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'content_width' => 'full',
                    'width' => ['unit' => '%', 'size' => 26],
                    'width_mobile' => ['unit' => '%', 'size' => 52, 'sizes' => []],
                ],
                'elements' => [
                    [
                        'id' => 'fislogoimg2',
                        'elType' => 'widget',
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => [
                                'url' => 'http://localhost/fisica/wp-content/uploads/2025/07/Imagem_do_WhatsApp_de_2025-07-14_a_s__23.42.06_25299ca5-removebg-preview.png',
                                'id' => 47,
                                'size' => '',
                                'alt' => 'Instituto de Física UERJ',
                                'source' => 'library',
                            ],
                            'align' => 'left',
                            'width' => ['unit' => '%', 'size' => 74, 'sizes' => []],
                            'width_mobile' => ['unit' => '%', 'size' => 96, 'sizes' => []],
                            'link_to' => 'custom',
                            'link' => ['url' => 'http://localhost/fisica/', 'is_external' => '', 'nofollow' => '', 'custom_attributes' => ''],
                        ],
                        'elements' => [],
                    ],
                ],
                'isInner' => true,
            ],
            [
                'id' => 'fisheadermenu2',
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'width' => ['unit' => '%', 'size' => 74],
                    'width_mobile' => ['unit' => '%', 'size' => 48, 'sizes' => []],
                ],
                'elements' => [
                    [
                        'id' => 'fismenunav2',
                        'elType' => 'widget',
                        'widgetType' => 'navigation-menu',
                        'settings' => [
                            'menu' => 'menu',
                            'navmenu_align' => 'right',
                            'resp_align' => 'right',
                            'padding_vertical_menu_item' => ['unit' => 'px', 'size' => 12, 'sizes' => []],
                            'padding_horizontal_menu_item' => ['unit' => 'px', 'size' => 16, 'sizes' => []],
                            'color_menu_item' => '#F4F8FC',
                            'toggle_color' => '#FFFFFF',
                            'menu_typography_typography' => 'custom',
                            'menu_typography_font_family' => 'Montserrat',
                            'menu_typography_font_size' => ['unit' => 'px', 'size' => 16, 'sizes' => []],
                            'menu_typography_font_weight' => '700',
                            'dropdown_divider_border' => 'none',
                            'item_border_border' => 'none',
                            'pointer' => 'underline',
                            'animation_line' => 'yes',
                            'background_color_dropdown_item' => '#0C2840',
                            'color_dropdown_item' => '#F4F8FC',
                            'color_dropdown_item_hover' => '#8FCBFF',
                            'color_menu_item_hover' => '#8FCBFF',
                        ],
                        'elements' => [],
                    ],
                ],
                'isInner' => true,
            ],
        ],
        'isInner' => false,
    ],
];

$footerData = [
    [
        'id' => 'fisfooter2',
        'elType' => 'container',
        'settings' => [
            '_css_classes' => 'fisica-site-footer',
            'flex_direction' => 'row',
            'content_width' => 'boxed',
            'boxed_width' => ['unit' => 'px', 'size' => 1284, 'sizes' => []],
            'flex_justify_content' => 'space-between',
            'flex_align_items' => 'center',
            'padding' => ['unit' => 'px', 'top' => '30', 'right' => '20', 'bottom' => '30', 'left' => '20', 'isLinked' => false],
        ],
        'elements' => [
            [
                'id' => 'fisfooterbrand',
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'content_width' => 'full',
                    'width' => ['unit' => '%', 'size' => 36],
                ],
                'elements' => [
                    [
                        'id' => 'fisfooterlogo2',
                        'elType' => 'widget',
                        'widgetType' => 'image',
                        'settings' => [
                            'image' => [
                                'url' => 'http://localhost/fisica/wp-content/uploads/2025/08/Assinatura-Conjunta-Sem-Tagline-Branco-03.png',
                                'id' => 169,
                                'size' => '',
                                'alt' => 'Instituto de Física UERJ',
                                'source' => 'library',
                            ],
                            'align' => 'left',
                            'width' => ['unit' => '%', 'size' => 60, 'sizes' => []],
                        ],
                        'elements' => [],
                    ],
                    [
                        'id' => 'fisfooterbrandtext',
                        'elType' => 'widget',
                        'widgetType' => 'text-editor',
                        'settings' => [
                            'editor' => '<p>Instituto de Física da UERJ com presença digital mais clara, mais elegante e mais profissional para ensino, pesquisa e extensão.</p>',
                            'text_color' => '#DCEBFF',
                            'align' => 'left',
                        ],
                        'elements' => [],
                    ],
                ],
                'isInner' => true,
            ],
            [
                'id' => 'fisfootercontact',
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'content_width' => 'full',
                    'width' => ['unit' => '%', 'size' => 32],
                ],
                'elements' => [
                    [
                        'id' => 'fisfooterheading1',
                        'elType' => 'widget',
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'Contato',
                            'header_size' => 'h3',
                            'title_color' => '#FFFFFF',
                        ],
                        'elements' => [],
                    ],
                    [
                        'id' => 'fisfootercontacttext',
                        'elType' => 'widget',
                        'widgetType' => 'text-editor',
                        'settings' => [
                            'editor' => '<p><strong>Endereço</strong><br>R. São Francisco Xavier, 524, Bloco B, Sala 3019, Maracanã, Rio de Janeiro, RJ</p><p><strong>Telefone</strong><br>(21) 2334-0045 | (21) 2334-0071</p>',
                            'text_color' => '#FFFFFF',
                            'align' => 'left',
                        ],
                        'elements' => [],
                    ],
                ],
                'isInner' => true,
            ],
            [
                'id' => 'fisfooterhours',
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'content_width' => 'full',
                    'width' => ['unit' => '%', 'size' => 32],
                ],
                'elements' => [
                    [
                        'id' => 'fisfooterheading2',
                        'elType' => 'widget',
                        'widgetType' => 'heading',
                        'settings' => [
                            'title' => 'Atendimento',
                            'header_size' => 'h3',
                            'title_color' => '#FFFFFF',
                        ],
                        'elements' => [],
                    ],
                    [
                        'id' => 'fisfooterhourstext',
                        'elType' => 'widget',
                        'widgetType' => 'text-editor',
                        'settings' => [
                            'editor' => '<p><strong>Funcionamento</strong><br>Segunda a sexta, das 9h às 18h</p><p><strong>Navegação rápida</strong><br><a href="http://localhost/fisica/">Página inicial</a><br><a href="http://localhost/fisica/index.php/contato/">Contato</a><br><a href="http://localhost/fisica/index.php/pessoas/">Pessoas</a></p>',
                            'text_color' => '#FFFFFF',
                            'align' => 'left',
                        ],
                        'elements' => [],
                    ],
                ],
                'isInner' => true,
            ],
        ],
        'isInner' => false,
    ],
];

backup_elementor_data($mysqli, 25, 'header');
backup_elementor_data($mysqli, 60, 'footer');
update_meta($mysqli, 25, '_elementor_data', json_encode($headerData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
update_meta($mysqli, 60, '_elementor_data', json_encode($footerData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
update_meta($mysqli, 25, '_elementor_version', '3.32.1');
update_meta($mysqli, 60, '_elementor_version', '3.32.1');
echo "Refined header/footer Elementor data\n";
?>
