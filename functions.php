<?php

// Function to enqueue styles and scripts
function my_theme_enqueue_styles() {
    // Enqueue the main theme stylesheet
    wp_enqueue_style('main-style', get_stylesheet_uri());

    // Enqueue Bootstrap CSS from the CDN
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

    // Enqueue Bootstrap JS from the CDN and jQuery (required by Bootstrap)
    wp_enqueue_script('jquery'); // jQuery (required by Bootstrap)
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

// Function to set up the theme
function my_theme_setup() {
    // Add support for post thumbnails
    add_theme_support('post-thumbnails');

    // Register custom menus
    register_nav_menus(array(
        'header-menu' => __('Header Menu', 'mytheme'),
    ));
}

add_action('after_setup_theme', 'my_theme_setup');
?>
