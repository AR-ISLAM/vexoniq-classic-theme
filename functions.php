<?php


function my_theme_enqueue_styles() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');


function my_theme_setup() {
    
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'header-menu' => __('Header Menu', 'mytheme'),
    ));
}
add_action('after_setup_theme', 'my_theme_setup');
