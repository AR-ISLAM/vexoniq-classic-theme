<?php
// Register custom post type for products
function create_product_post_type() {
    $labels = array(
        'name'                     => 'Products',
        'singular_name'            => 'Product',
        'menu_name'                => 'Products',
        'name_admin_bar'           => 'Product',
        'archives'                 => 'Product Archives',
        'attributes'               => 'Item Attributes',
        'parent_item_colon'        => 'Parent Item:',
        'all_items'                => 'All Items',
        'add_new_item'             => 'Add New Item',
        'add_new'                  => 'Add New',
        'new_item'                 => 'New Item',
        'edit_item'                => 'Edit Item',
        'update_item'              => 'Update Item',
        'view_item'                => 'View Item',
        'view_items'               => 'View Items',
        'search_items'             => 'Search Item',
        'not_found'                => 'Not Found',
        'not_found_in_trash'       => 'Not Found in Trash',
        'featured_image'           => 'Featured Image',
        'set_featured_image'       => 'Set Featured Image',
        'remove_featured_image'    => 'Remove Featured Image',
        'use_featured_image'       => 'Use as Featured Image',
        'insert_into_item'         => 'Insert into Item',
        'uploaded_to_this_item'    => 'Uploaded to This Item',
        'items_list'               => 'Items List',
        'items_list_navigation'    => 'Items List Navigation',
        'filter_items_list'        => 'Filter Items List',
    );

    $args = array(
        'label'                  => 'Product',
        'description'            => 'Product Description',
        'labels'                 => $labels,
        'public'                 => true,
        'menu_icon'              => 'dashicons-cart',
        'supports'               => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite'                => array('slug' => 'products'),
        'taxonomies'             => array('product_category'),
        'hierarchical'           => false,
        'show_ui'                => true,
        'show_in_menu'           => true,
        'menu_position'          => 5,
        'show_in_admin_bar'      => true,
        'show_in_nav_menus'      => true,
        'can_export'             => true,
        'has_archive'            => true,
        'exclude_from_search'    => false,
        'publicly_queryable'     => true,
        'capability_type'        => 'post',
        'show_in_rest' => true, // If you're using Gutenberg
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
        'rewrite'            => array('slug' => 'product-category', 'with_front' => false),
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
add_action('init', 'create_product_taxonomies');


// Function to create custom permalinks for products with category hierarchy
function custom_product_permalink($permalink, $post) {
    if ($post->post_type === 'product') {
        $terms = get_the_terms($post->ID, 'product_category');
        $category_slug = '';

        if ($terms && !is_wp_error($terms)) {
            // Get the deepest category structure
            $category_slug = get_full_category_path($terms);
        }

        // Append the product ID to the URL
        return home_url("/products/{$category_slug}/{$post->post_name}-{$post->ID}");
    }

    return $permalink;
}
add_filter('post_type_link', 'custom_product_permalink', 10, 2);

// Function to get full category path (parent -> child)
function get_full_category_path($terms) {
    $deepest_category = null;
    
    foreach ($terms as $term) {
        if (!$deepest_category || $term->parent > 0) {
            $deepest_category = $term;
        }
    }

    if ($deepest_category) {
        return get_category_hierarchy($deepest_category);
    }

    return '';
}

// Recursive function to get full category hierarchy
function get_category_hierarchy($term) {
    $category_hierarchy = [];
    
    while ($term->parent) {
        $category_hierarchy[] = $term->slug;
        $term = get_term($term->parent, 'product_category');
    }
    
    $category_hierarchy[] = $term->slug; // Add the top-level category
    return implode('/', array_reverse($category_hierarchy));
}

function custom_product_rewrite_rules() {
    // Match full product URL: /products/parent-category/child-category/product-name-id/
    add_rewrite_rule(
        '^products/([^/]+)/([^/]+)/([^/]+)/([^/]+)-([0-9]+)/?$',
        'index.php?post_type=product&p=$matches[5]',
        'top'
    );

    // Match full product URL: /products/parent-category/child-category/product-name-id/ (for 2 categories)
    add_rewrite_rule(
        '^products/([^/]+)/([^/]+)/([^/]+)-([0-9]+)/?$',
        'index.php?post_type=product&p=$matches[4]',
        'top'
    );

    // Rewrite rule for category-based URLs: /products/category-name/
    add_rewrite_rule(
        '^products/([^/]+)/?$',
        'index.php?product_category=$matches[1]',
        'top'
    );

    // Rewrite rule for category-based URLs with subcategories: /products/parent-category/child-category/
    add_rewrite_rule(
        '^products/([^/]+)/([^/]+)/?$',
        'index.php?product_category=$matches[2]',
        'top'
    );
}
add_action('init', 'custom_product_rewrite_rules');

function custom_add_query_vars($vars) {
    $vars[] = 'product_category';
    return $vars;
}
add_filter('query_vars', 'custom_add_query_vars');

function custom_template_redirect() {
    if (get_query_var('product_category')) {
        include get_template_directory() . '/archive-product.php';
        exit;
    }
}
add_action('template_redirect', 'custom_template_redirect');




