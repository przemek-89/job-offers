<?php

function custom_post_type() {
  // JOB POST TYPE
  $labels_job = [
    'name' => 'Oferty Pracy',
    'singular_name' => 'Oferta Pracy',
    'add_new' => 'Dodaj Nową',
    'add_new_item' => 'Dodaj Nową Ofertę',
    'edit_item' => 'Edytuj Ofertę',
    'new_item' => 'Nowa Oferta',
    'view_item' => 'Zobacz Ofertę',
    'search_items' => 'Szukaj Ofert',
    'not_found' => 'Nie znaleziono żadnych ofert',
    'not_found_in_trash' => 'Nie znaleziono ofert w koszu',
    ];

    $args_job = [
        'labels' => $labels_job,
        'public' => true,
        'supports' => ['title', 'thumbnail', 'custom_fields'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'jobs'],
        'show_in_rest' => true,
    ];

    register_post_type('job', $args_job);

    // JOB CATEGORY TAXONOMY
    $labels_cat = [
        'name' => 'Kategorie Ofert',
        'singular_name' => 'Kategoria Ofert',
        'search_items' => 'Szukaj Kategorii',
        'all_items' => 'Wszystkie Kategorie',
        'edit_item' => 'Edytuj Kategorię',
        'update_item' => 'Aktualizuj Kategorię',
        'add_new_item' => 'Dodaj Nową Kategorię',
        'new_item_name' => 'Nowa Nazwa Kategorii',
    ];

    $args_cat = [
        'labels' => $labels_cat,
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
    ];

    register_taxonomy('job-category', ['job'], $args_cat);
}

add_action('init', 'custom_post_type', 0);

// Add job category column in job post
add_filter('manage_job_posts_columns', 'add_job_category_column');
function add_job_category_column($columns) {
    $columns['job_category'] = __('Kategoria', 'text-domain');
    return $columns;
}

add_action('manage_job_posts_custom_column', 'display_job_category_column', 10, 2);
function display_job_category_column($column, $post_id) {
    if ($column == 'job_category') {
        $terms = get_the_terms($post_id, 'job-category');
        if (!empty($terms) && !is_wp_error($terms)) {
            $term_names = wp_list_pluck($terms, 'name');
            echo esc_html(implode(', ', $term_names));
        } else {
            echo __('Brak kategorii', 'text-domain');
        }
    }
}

add_filter('manage_job_posts_columns', 'reorder_job_columns');
function reorder_job_columns($columns) {
    if (get_post_type() == 'job') {
        $new_columns = [];
        foreach ($columns as $key => $title) {
            $new_columns[$key] = $title;
            if ($key == 'title') {
                $new_columns['job_category'] = __('Kategoria', 'text-domain');
            }
        }
        return $new_columns;
    }
    return $columns;
}