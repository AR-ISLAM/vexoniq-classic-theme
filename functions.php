<?php
// Include custom function files
require_once get_template_directory() . '/function_components/add-new-product-functions.php';
require_once get_template_directory() . '/function_components/add-product-images-functions.php';
require_once get_template_directory() . '/function_components/related-products-functions.php';


// Enqueue styles and scripts
function my_theme_enqueue_styles() {
    // Enqueue the main theme stylesheet
    wp_enqueue_style('main-style', get_stylesheet_uri());
    // Enqueue the Dashicons icon font
    wp_enqueue_style('dashicons');
    // Enqueue Bootstrap CSS from the CDN
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    // Enqueue Bootstrap JS from the CDN and jQuery (required by Bootstrap)
    wp_enqueue_script('jquery'); // jQuery (required by Bootstrap)
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    // Enqueue all styles from assets/styles
    wp_enqueue_style('topbar-style', get_template_directory_uri() . '/assets/styles/topbar.css', array(), '1.0', 'all');
    wp_enqueue_style('floating-contact-style', get_template_directory_uri() . '/assets/styles/floating-contact.css', array(), '1.0', 'all');
    wp_enqueue_style('navbar-style', get_template_directory_uri() . '/assets/styles/navbar.css', array(), '1.0', 'all');
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

// Register custom post type for products
function create_product_post_type() {
    $labels = array(
        'name'               => __('Products'),
        'singular_name'      => __('Product'),
        'menu_name'          => 'Products',
        'name_admin_bar'     => 'Product',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Product',
        'new_item'           => 'New Product',
        'edit_item'          => 'Edit Product',
        'view_item'          => 'View Product',
        'all_items'          => 'All Products',
        'search_items'       => 'Search Products',
        'not_found'          => 'No products found.',
        'not_found_in_trash' => 'No products found in Trash.',
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-cart',
        'has_archive'        => true,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite'            => array('slug' => 'products/%product_category%/%product%'),
        'taxonomies'         => array('product_category'),
        'show_in_nav_menus'  => true,
    );
    register_post_type('product', $args);
}
add_action('init', 'create_product_post_type');

// Register custom taxonomies for products
function create_product_taxonomies() {
    // Product Categories (Hierarchical - Supports Parent-Child)
    register_taxonomy('product_category', 'product', array(
        'labels' => array(
            'name'              => 'Product Categories',
            'singular_name'     => 'Product Category',
            'search_items'      => 'Search Product Categories',
            'all_items'         => 'All Product Categories',
            'parent_item'       => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'edit_item'         => 'Edit Product Category',
            'update_item'       => 'Update Product Category',
            'add_new_item'      => 'Add New Product Category',
            'new_item_name'     => 'New Product Category Name',
            'menu_name'         => 'Product Categories',
        ),
        'hierarchical'       => true, // Parent-child categories
        'show_admin_column'  => true,
        'show_ui'            => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'product-category'),
        'show_in_menu'       => 'edit.php?post_type=product', // Show under "Products"
    ));

    // Product Tags (Non-Hierarchical - Like Hashtags for SEO)
    register_taxonomy('product_tag', 'product', array(
        'labels' => array(
            'name'              => 'Product Tags',
            'singular_name'     => 'Product Tag',
            'search_items'      => 'Search Product Tags',
            'all_items'         => 'All Product Tags',
            'edit_item'         => 'Edit Product Tag',
            'update_item'       => 'Update Product Tag',
            'add_new_item'      => 'Add New Product Tag',
            'new_item_name'     => 'New Product Tag Name',
            'menu_name'         => 'Product Tags',
        ),
        'hierarchical'       => false, // Acts like hashtags
        'show_admin_column'  => true,
        'show_ui'            => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'product-tag'),
        'show_in_menu'       => 'edit.php?post_type=product', // Show under "Products"
    ));
}

// Hide the Custom Fields box for the Product post type
function hide_custom_fields_for_products() {
    remove_meta_box('postcustom', 'product', 'normal'); // Hides the Custom Fields box for products
}
add_action('admin_menu', 'hide_custom_fields_for_products');

function custom_product_rewrite_rules() {
    add_rewrite_rule(
        '([^/]+)/([^/]+)/?$',
        'index.php?product=$matches[2]',
        'top'
    );
}
add_action('init', 'custom_product_rewrite_rules');

function custom_product_permalink($permalink, $post) {
    if ($post->post_type == 'product') {
        // Get the product categories (replace 'product_cat' with your taxonomy name if different)
        $terms = get_the_terms($post->ID, 'product_category');
        $category_path = '';

        if ($terms && !is_wp_error($terms)) {
            // Sort categories hierarchically
            $sorted_terms = [];
            foreach ($terms as $term) {
                $sorted_terms[$term->term_id] = $term;
            }

            // Sort categories based on hierarchy
            usort($sorted_terms, function ($a, $b) {
                return ($a->parent <=> $b->parent);
            });

            // Build category path
            foreach ($sorted_terms as $term) {
                $category_path .= $term->slug . '/';
            }
        }

        // Ensure the category path doesn't have a trailing slash
        $category_path = rtrim($category_path, '/');

        // Set the new permalink structure
        $permalink = home_url('/products/' . $category_path . $post->post_title . '-' . $post->ID . '/');
    }
    
    return $permalink;
}
add_filter('post_type_link', 'custom_product_permalink', 10, 2);


?>