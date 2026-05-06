<?php
require 'wp-load.php';

$menu_id = 3;
$items = wp_get_nav_menu_items($menu_id, ['post_status' => 'any']);

if (!$items) {
    exit("menu_not_found\n");
}

$by_title = [];
foreach ($items as $item) {
    $by_title[$item->title] = $item;
}

$o_instituto = $by_title['O Instituto'] ?? null;
$sobre = $by_title['Sobre o Instituto'] ?? null;

if (!$o_instituto || !$sobre) {
    exit("required_items_missing\n");
}

function fisica_find_page_by_title_or_slug(array $candidates): ?WP_Post {
    $pages = get_posts([
        'post_type' => 'page',
        'post_status' => ['publish', 'private', 'draft'],
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);

    foreach ($pages as $page) {
        $title = remove_accents(wp_strip_all_tags($page->post_title));
        $slug = remove_accents($page->post_name);
        foreach ($candidates as $candidate) {
            $needle = remove_accents($candidate);
            if ($title === $needle || $slug === $needle) {
                return $page;
            }
        }
    }

    return null;
}

function fisica_upsert_menu_item(int $menu_id, ?WP_Post $existing, array $args): int {
    $item_id = $existing ? (int) $existing->ID : 0;
    wp_update_nav_menu_item($menu_id, $item_id, $args);
    return $item_id ?: (int) get_posts([
        'post_type' => 'nav_menu_item',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'orderby' => 'ID',
        'order' => 'DESC',
        'title' => $args['menu-item-title'] ?? '',
    ])[0]->ID;
}

$page_graduacao = fisica_find_page_by_title_or_slug(['Graduação', 'graduacao']);
$page_pos = fisica_find_page_by_title_or_slug(['Pós Graduação', 'Pos Graduação', 'pos-graduacao', 'pos-graduacao']);
$page_extensao = fisica_find_page_by_title_or_slug(['Extensão', 'extensao']);
$page_linha = fisica_find_page_by_title_or_slug(['Linha do Tempo', 'linha-do-tempo']);
$page_plano = fisica_find_page_by_title_or_slug(['Plano de Desenvolvimento do Instituto', 'plano-de-desenvolvimento-do-instituto']);

$ensino_existing = $by_title['Ensino'] ?? null;
$graduacao_existing = $by_title['Graduação'] ?? null;
$pos_existing = $by_title['Pós Graduação'] ?? null;
$extensao_existing = $by_title['Extensão'] ?? null;
$linha_existing = $by_title['Linha do Tempo'] ?? null;
$plano_existing = $by_title['Plano de Desenvolvimento do Instituto'] ?? null;

$ensino_id = fisica_upsert_menu_item($menu_id, $ensino_existing, [
    'menu-item-title' => 'Ensino',
    'menu-item-url' => '#',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => (int) $o_instituto->ID,
    'menu-item-position' => 3,
    'menu-item-type' => 'custom',
]);

$graduacao_id = fisica_upsert_menu_item($menu_id, $graduacao_existing, $page_graduacao ? [
    'menu-item-title' => 'Graduação',
    'menu-item-object-id' => $page_graduacao->ID,
    'menu-item-object' => 'page',
    'menu-item-type' => 'post_type',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => $ensino_id,
    'menu-item-position' => 4,
] : [
    'menu-item-title' => 'Graduação',
    'menu-item-url' => '#',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => $ensino_id,
    'menu-item-position' => 4,
    'menu-item-type' => 'custom',
]);

$pos_id = fisica_upsert_menu_item($menu_id, $pos_existing, $page_pos ? [
    'menu-item-title' => 'Pós Graduação',
    'menu-item-object-id' => $page_pos->ID,
    'menu-item-object' => 'page',
    'menu-item-type' => 'post_type',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => $ensino_id,
    'menu-item-position' => 5,
] : [
    'menu-item-title' => 'Pós Graduação',
    'menu-item-url' => '#',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => $ensino_id,
    'menu-item-position' => 5,
    'menu-item-type' => 'custom',
]);

$extensao_id = fisica_upsert_menu_item($menu_id, $extensao_existing, $page_extensao ? [
    'menu-item-title' => 'Extensão',
    'menu-item-object-id' => $page_extensao->ID,
    'menu-item-object' => 'page',
    'menu-item-type' => 'post_type',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => $ensino_id,
    'menu-item-position' => 6,
] : [
    'menu-item-title' => 'Extensão',
    'menu-item-url' => '#',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => $ensino_id,
    'menu-item-position' => 6,
    'menu-item-type' => 'custom',
]);

$linha_id = fisica_upsert_menu_item($menu_id, $linha_existing, $page_linha ? [
    'menu-item-title' => 'Linha do Tempo',
    'menu-item-object-id' => $page_linha->ID,
    'menu-item-object' => 'page',
    'menu-item-type' => 'post_type',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => (int) $o_instituto->ID,
    'menu-item-position' => 7,
] : [
    'menu-item-title' => 'Linha do Tempo',
    'menu-item-url' => '#',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => (int) $o_instituto->ID,
    'menu-item-position' => 7,
    'menu-item-type' => 'custom',
]);

$plano_id = fisica_upsert_menu_item($menu_id, $plano_existing, $page_plano ? [
    'menu-item-title' => 'Plano de Desenvolvimento do Instituto',
    'menu-item-object-id' => $page_plano->ID,
    'menu-item-object' => 'page',
    'menu-item-type' => 'post_type',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => (int) $o_instituto->ID,
    'menu-item-position' => 8,
] : [
    'menu-item-title' => 'Plano de Desenvolvimento do Instituto',
    'menu-item-url' => '#',
    'menu-item-status' => 'publish',
    'menu-item-parent-id' => (int) $o_instituto->ID,
    'menu-item-position' => 8,
    'menu-item-type' => 'custom',
]);

$refresh_items = wp_get_nav_menu_items($menu_id, ['post_status' => 'any']);
$map = [];
foreach ($refresh_items as $item) {
    $map[$item->ID] = $item;
}

$ordered_ids = [
    24,
    305,
    $ensino_id,
    $graduacao_id,
    $pos_id,
    $extensao_id,
    $linha_id,
    $plano_id,
    20,
    318,
    317,
    316,
    315,
    104,
    99,
    122,
    151,
    150,
    303,
    304,
    1014,
    1010,
    1011,
    1012,
    508,
    306,
    511,
    22,
];

$position = 1;
foreach ($ordered_ids as $id) {
    if (!isset($map[$id])) {
        continue;
    }

    wp_update_post([
        'ID' => $id,
        'menu_order' => $position,
    ]);

    $position++;
}

echo 'updated_menu=' . $menu_id . PHP_EOL;
echo 'ensino=' . $ensino_id . PHP_EOL;
echo 'graduacao_page=' . ($page_graduacao ? $page_graduacao->ID : 'none') . PHP_EOL;
echo 'pos_page=' . ($page_pos ? $page_pos->ID : 'none') . PHP_EOL;
echo 'extensao_page=' . ($page_extensao ? $page_extensao->ID : 'none') . PHP_EOL;
echo 'linha_page=' . ($page_linha ? $page_linha->ID : 'none') . PHP_EOL;
echo 'plano_page=' . ($page_plano ? $page_plano->ID : 'none') . PHP_EOL;
