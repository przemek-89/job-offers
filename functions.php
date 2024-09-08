<?php

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

if (! function_exists('\Roots\bootloader')) {
    wp_die(
        __('You need to install Acorn to use this theme.', 'sage'),
        '',
        [
            'link_url' => 'https://roots.io/acorn/docs/installation/',
            'link_text' => __('Acorn Docs: Installation', 'sage'),
        ]
    );
}

\Roots\bootloader()->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });


function custom_enqueue_scripts() {
    wp_enqueue_script('ajax-script', get_template_directory_uri() . '/resources/scripts/ajax-filter.js', array('jquery'), null, true);

    wp_localize_script('ajax-script', 'ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');

function ajax_filter_posts() {
    $category_id = isset($_POST['category']) ? intval($_POST['category']) : 0;
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $per_page = isset($_POST['perPage']) ? intval($_POST['perPage']) : 5;

    $args = array(
        'post_type' => 'job',
        'posts_per_page' => $per_page,
        'paged' => $paged,
    );

    if ($category_id != 0) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'job-category',
                'field' => 'term_id',
                'terms' => $category_id,
            ),
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $posts_html = array();

        while ($query->have_posts()) {
            $query->the_post();
            $posts_html[] = \Roots\view('components.job-card', ['post' => get_post()])->render();
        }

        wp_reset_postdata();

        $has_more_posts = $query->max_num_pages > $paged;

        wp_send_json_success(array(
            'posts' => $posts_html,
            'has_more_posts' => $has_more_posts,
        ));
    } else {
        wp_send_json_error('No posts found');
    }
}

add_action('wp_ajax_filter_posts', 'ajax_filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'ajax_filter_posts');
