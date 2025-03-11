<?php
// Include custom function files
require_once get_template_directory() . '/function_components/add-new-product-functions.php';
require_once get_template_directory() . '/function_components/add-product-images-functions.php';
require_once get_template_directory() . '/function_components/related-products-functions.php';
require_once get_template_directory() . '/function_components/product-cpt.php';


// Enqueue styles and scripts
function my_theme_enqueue_styles()
{
    // Enqueue the main theme stylesheet
    wp_enqueue_style('main-style', get_stylesheet_uri());
    // Enqueue the Dashicons icon font
    wp_enqueue_style('dashicons');

     // Bootstrap CSS
     wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');

     // Bootstrap JS (Bundle includes Popper.js)
     wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), null, true);

    // Enqueue all styles from assets/styles
    wp_enqueue_style('topbar-style', get_template_directory_uri() . '/assets/styles/topbar.css', array(), '1.0', 'all');
    wp_enqueue_style('floating-contact-style', get_template_directory_uri() . '/assets/styles/floating-contact.css', array(), '1.0', 'all');
    wp_enqueue_style('navbar-style', get_template_directory_uri() . '/assets/styles/navbar.css', array(), '1.0', 'all');
    if (is_front_page()) { // Load CSS only on the homepage
        wp_enqueue_style('front-page-style', get_template_directory_uri() . '/assets/styles/front-page.css', array(), '1.0', 'all');
    }
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

// Function to set up the theme
function my_theme_setup()
{
    // Add support for post thumbnails
    add_theme_support('post-thumbnails');
    // Register custom menus
    register_nav_menus(array(
        'header-menu' => __('Header Menu', 'mytheme'),
    ));
}
add_action('after_setup_theme', 'my_theme_setup');

function custom_product_template($template)
{
    // Check if it's a single 'product' post type
    if (is_singular('product')) {
        // Check if the template file exists in the theme directory
        if (locate_template('xinsheng-elect.php')) {
            return get_template_directory() . '/xinsheng-elect.php';
        }
    }
    return $template;
}
add_filter('template_include', 'custom_product_template');


?>