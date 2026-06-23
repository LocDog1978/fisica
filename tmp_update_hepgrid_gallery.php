<?php
require __DIR__ . '/wp-load.php';

global $wpdb;

$post_id = 656;
$post = get_post($post_id);

if (!$post) {
    fwrite(STDERR, "Post {$post_id} nao encontrado.\n");
    exit(1);
}

$addition = '<a class="fisica-gallery-item" href="http://localhost/fisica/wp-content/uploads/2026/06/GEO_5476-2.jpg"><img src="http://localhost/fisica/wp-content/uploads/2026/06/GEO_5476-2.jpg" alt="Laboratório HEPGrid"></a><a class="fisica-gallery-item" href="http://localhost/fisica/wp-content/uploads/2026/06/GEO_6491-2.jpg"><img src="http://localhost/fisica/wp-content/uploads/2026/06/GEO_6491-2.jpg" alt="Laboratório HEPGrid"></a>';

$content = (string) $post->post_content;
if (strpos($content, 'GEO_5476-2.jpg') !== false || strpos($content, 'GEO_6491-2.jpg') !== false) {
    echo "ALREADY_UPDATED_POST\n";
} else {
    $needle = '</div></div></section>';
    $replacement = $addition . $needle;
    $updated_content = str_replace($needle, $replacement, $content, $count);

    if ($count !== 1) {
        fwrite(STDERR, "Nao foi possivel localizar o fechamento esperado no post_content.\n");
        exit(1);
    }

    $ok = $wpdb->update(
        $wpdb->posts,
        ['post_content' => $updated_content],
        ['ID' => $post_id],
        ['%s'],
        ['%d']
    );

    if ($ok === false) {
        fwrite(STDERR, "Falha ao atualizar post_content.\n");
        exit(1);
    }
}

$meta = get_post_meta($post_id, '_elementor_data', true);
if (strpos($meta, 'GEO_5476-2.jpg') !== false || strpos($meta, 'GEO_6491-2.jpg') !== false) {
    echo "ALREADY_UPDATED_META\n";
} else {
    $needle_meta = '</div></div></section>';
    $replacement_meta = $addition . $needle_meta;
    $updated_meta = str_replace($needle_meta, $replacement_meta, $meta, $count_meta);

    if ($count_meta !== 1) {
        fwrite(STDERR, "Nao foi possivel localizar o fechamento esperado em _elementor_data.\n");
        exit(1);
    }

    $meta_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT meta_id FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s LIMIT 1",
            $post_id,
            '_elementor_data'
        )
    );

    if (!$meta_id) {
        fwrite(STDERR, "Meta _elementor_data nao encontrada.\n");
        exit(1);
    }

    $ok_meta = $wpdb->update(
        $wpdb->postmeta,
        ['meta_value' => $updated_meta],
        ['meta_id' => (int) $meta_id],
        ['%s'],
        ['%d']
    );

    if ($ok_meta === false) {
        fwrite(STDERR, "Falha ao atualizar _elementor_data.\n");
        exit(1);
    }
}

delete_post_meta($post_id, '_elementor_element_cache');

echo "HEPGRID_GALLERY_OK\n";
