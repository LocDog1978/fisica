<?php
require dirname(__DIR__, 2) . '/wp-load.php';

$post_id = 1215;
global $wpdb;

$post_content = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT post_content FROM {$wpdb->posts} WHERE ID = %d LIMIT 1",
        $post_id
    )
);

if (!is_string($post_content) || $post_content === '') {
    fwrite(STDERR, "Post content not found\n");
    exit(1);
}

// 1) Force the Elementor HTML widget source to mirror the already-correct post_content.
$elementor_data = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s LIMIT 1",
        $post_id,
        '_elementor_data'
    )
);

if (!is_string($elementor_data) || $elementor_data === '') {
    fwrite(STDERR, "_elementor_data not found\n");
    exit(1);
}

$data = json_decode($elementor_data, true);

if (!is_array($data) || !isset($data[0]['elements'][0]['settings']['html'])) {
    fwrite(STDERR, "Unexpected _elementor_data structure\n");
    exit(1);
}

$data[0]['elements'][0]['settings']['html'] = $post_content;
$updated_elementor_data = wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$wpdb->update(
    $wpdb->postmeta,
    ['meta_value' => $updated_elementor_data],
    [
        'post_id' => $post_id,
        'meta_key' => '_elementor_data',
    ],
    ['%s'],
    ['%d', '%s']
);

// 2) Force the rendered Elementor cache to carry the same section markup.
$elementor_cache = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s LIMIT 1",
        $post_id,
        '_elementor_element_cache'
    )
);

if (is_string($elementor_cache) && $elementor_cache !== '') {
    $escaped_post_content = str_replace(
        ['\\', '"', "\r", "\n", '/'],
        ['\\\\', '\\"', '', '\n', '\/'],
        $post_content
    );

    $updated_cache = preg_replace(
        '/<section class=\\\\\"fisica-detail-page\\\\\">.*?<\\\\\/section>/s',
        $escaped_post_content,
        $elementor_cache,
        1
    );

    if (is_string($updated_cache) && $updated_cache !== '') {
        $wpdb->update(
            $wpdb->postmeta,
            ['meta_value' => $updated_cache],
            [
                'post_id' => $post_id,
                'meta_key' => '_elementor_element_cache',
            ],
            ['%s'],
            ['%d', '%s']
        );
    }
}

$check = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s LIMIT 1",
        $post_id,
        '_elementor_data'
    )
);

preg_match_all('/Captura-de-tela-2026-06-09-(155035|155102)\.png/u', $check, $matches);

foreach (($matches[0] ?? []) as $index => $filename) {
    echo ($index + 1) . "\t" . $filename . PHP_EOL;
}
