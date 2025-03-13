<?php
// Register custom post type for products
function create_product_post_type()
{
    $labels = array(
        'name' => 'Products',
        'singular_name' => 'Product',
        'menu_name' => 'Products',
        'name_admin_bar' => 'Product',
        'archives' => 'Product Archives',
        'attributes' => 'Item Attributes',
        'parent_item_colon' => 'Parent Item:',
        'all_items' => 'All Items',
        'add_new_item' => 'Add New Item',
        'add_new' => 'Add New',
        'new_item' => 'New Item',
        'edit_item' => 'Edit Item',
        'update_item' => 'Update Item',
        'view_item' => 'View Item',
        'view_items' => 'View Items',
        'search_items' => 'Search Item',
        'not_found' => 'Not Found',
        'not_found_in_trash' => 'Not Found in Trash',
        'featured_image' => 'Featured Image',
        'set_featured_image' => 'Set Featured Image',
        'remove_featured_image' => 'Remove Featured Image',
        'use_featured_image' => 'Use as Featured Image',
        'insert_into_item' => 'Insert into Item',
        'uploaded_to_this_item' => 'Uploaded to This Item',
        'items_list' => 'Items List',
        'items_list_navigation' => 'Items List Navigation',
        'filter_items_list' => 'Filter Items List',
    );

    $args = array(
        'label' => 'Product',
        'description' => 'Product Description',
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-cart',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'products'),
        'taxonomies' => array('product_category'),
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true, // If you're using Gutenberg
    );

    register_post_type('product', $args);
}
add_action('init', 'create_product_post_type');

// Register custom taxonomies for products
function create_product_taxonomies()
{
    // Product Categories (Hierarchical - Supports Parent-Child)
    register_taxonomy('product_category', 'product', array(
        'labels' => array(
            'name' => 'Product Categories',
            'singular_name' => 'Product Category',
            'search_items' => 'Search Product Categories',
            'all_items' => 'All Product Categories',
            'parent_item' => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'edit_item' => 'Edit Product Category',
            'update_item' => 'Update Product Category',
            'add_new_item' => 'Add New Product Category',
            'new_item_name' => 'New Product Category Name',
            'menu_name' => 'Product Categories',
        ),
        'hierarchical' => true, // Parent-child categories
        'show_admin_column' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'products', 'with_front' => false),
        'show_in_menu' => 'edit.php?post_type=product', // Show under "Products"
    ));

    // Product Tags (Non-Hierarchical - Like Hashtags for SEO)
    register_taxonomy('product_tag', 'product', array(
        'labels' => array(
            'name' => 'Product Tags',
            'singular_name' => 'Product Tag',
            'search_items' => 'Search Product Tags',
            'all_items' => 'All Product Tags',
            'edit_item' => 'Edit Product Tag',
            'update_item' => 'Update Product Tag',
            'add_new_item' => 'Add New Product Tag',
            'new_item_name' => 'New Product Tag Name',
            'menu_name' => 'Product Tags',
        ),
        'hierarchical' => false, // Acts like hashtags
        'show_admin_column' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'product-tag'),
        'show_in_menu' => 'edit.php?post_type=product', // Show under "Products"
    ));
}
add_action('init', 'create_product_taxonomies');


// Function to create custom permalinks for products with category hierarchy
function custom_product_permalink($permalink, $post)
{
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
function get_full_category_path($terms)
{
    $deepest_category = null;
    $max_depth = 0;

    foreach ($terms as $term) {
        $depth = get_category_depth($term);
        if ($depth > $max_depth) {
            $max_depth = $depth;
            $deepest_category = $term;
        }
    }

    if ($deepest_category) {
        return get_category_hierarchy($deepest_category);
    }

    return '';
}

// Function to get the depth level of a category
function get_category_depth($term)
{
    $depth = 0;
    while ($term->parent) {
        $depth++;
        $term = get_term($term->parent, 'product_category');
    }
    return $depth;
}

// Recursive function to get the full category hierarchy
function get_category_hierarchy($term)
{
    $category_hierarchy = [];

    while ($term) {
        $category_hierarchy[] = $term->slug;
        $term = ($term->parent) ? get_term($term->parent, 'product_category') : null;
    }

    return implode('/', array_reverse($category_hierarchy));
}

function custom_product_rewrite_rules()
{
    // Match product URL: /products/parent-category/child-category/product-name-id/
    add_rewrite_rule(
        '^products/(.+)/([^/]+)-([0-9]+)/?$',
        'index.php?post_type=product&p=$matches[3]',
        'top'
    );

    // Match URLs with up to 3 category levels: /products/level-1/level-2/level-3/
    add_rewrite_rule(
        '^products/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?product_category=$matches[3]',
        'top'
    );

    // Match URLs with up to 2 category levels: /products/level-1/level-2/
    add_rewrite_rule(
        '^products/([^/]+)/([^/]+)/?$',
        'index.php?product_category=$matches[2]',
        'top'
    );

    // Match URLs with 1 category level: /products/level-1/
    add_rewrite_rule(
        '^products/([^/]+)/?$',
        'index.php?product_category=$matches[1]',
        'top'
    );
}
add_action('init', 'custom_product_rewrite_rules');



function custom_add_query_vars($vars)
{
    $vars[] = 'product_category';
    return $vars;
}
add_filter('query_vars', 'custom_add_query_vars');

function custom_template_redirect()
{
    if (get_query_var('product_category')) {
        include get_template_directory() . '/archive-product.php';
        exit;
    }
}
add_action('template_redirect', 'custom_template_redirect');


// Adding the product description and introduction
function add_product_category_meta_fields()
{
    register_meta('product_category', 'product_category_intro', array(
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'wp_kses_post'
    ));

    register_meta('product_category', 'product_category_content', array(
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'wp_kses_post'
    ));
    register_meta('product_category', 'open_type', array(
        'type' => 'boolean',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'rest_sanitize_boolean'
    ));
    register_meta('product_category', 'term_order', [
        'type' => 'integer',
        'description' => 'Custom sorting order for product categories',
        'single' => true,
        'default' => 0,
        'show_in_rest' => true, // Enables REST API support if needed
    ]);
}
add_action('init', 'add_product_category_meta_fields');

// Add SEO Content and Open Type field to Add Category Form
function add_product_category_custom_fields()
{
    ?>
    <div class="form-field">
        <label for="product_category_intro"><?php _e('Category Introduction', 'textdomain'); ?></label>
        <?php wp_editor('', 'product_category_intro', array(
            'textarea_name' => 'product_category_intro',
            'media_buttons' => false,
            'teeny' => true,
            'quicktags' => true
        )); ?>
        <p class="description"><?php _e('Add a brief introduction for this product category.', 'textdomain'); ?></p>
    </div>

    <div class="form-field">
        <label for="seo_content"><?php _e('SEO Content', 'textdomain'); ?></label>
        <?php wp_editor('', 'seo_content', array(
            'textarea_name' => 'seo_content',
            'media_buttons' => false,
            'teeny' => true,
            'quicktags' => true
        )); ?>
        <p class="description"><?php _e('Add SEO content for this product category.', 'textdomain'); ?></p>
    </div>

    <div class="form-field">
        <label for="open_type"><?php _e('Open Type', 'textdomain'); ?></label>
        <input type="checkbox" name="open_type" id="open_type" value="1">
        <p class="description"><?php _e('Check if this category is open type.', 'textdomain'); ?></p>
    </div>

    <div class="form-field">
        <label for="term_order"><?php _e('Sort Order', 'textdomain'); ?></label>
        <input type="number" name="term_order" id="term_order" value="0">
        <p class="description"><?php _e('Set a custom sorting order for categories.', 'textdomain'); ?></p>
    </div>

    <?php
}
add_action('product_category_add_form_fields', 'add_product_category_custom_fields', 10, 2);

// Add Fields to the Edit Category Form
function edit_product_category_custom_fields($term)
{
    $product_category_intro = get_term_meta($term->term_id, 'product_category_intro', true);
    $seo_content = get_term_meta($term->term_id, 'seo_content', true);
    $open_type = get_term_meta($term->term_id, 'open_type', true);
    $term_order = get_term_meta($term->term_id, 'term_order', true); // Fetch term_order value

    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="product_category_intro"><?php _e('Category Introduction', 'textdomain'); ?></label>
        </th>
        <td>
            <?php wp_editor($product_category_intro, 'product_category_intro', array(
                'textarea_name' => 'product_category_intro',
                'media_buttons' => false,
                'teeny' => true,
                'quicktags' => true
            )); ?>
            <p class="description"><?php _e('Add a brief introduction for this product category.', 'textdomain'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="seo_content"><?php _e('SEO Content', 'textdomain'); ?></label>
        </th>
        <td>
            <?php wp_editor($seo_content, 'seo_content', array(
                'textarea_name' => 'seo_content',
                'media_buttons' => false,
                'teeny' => true,
                'quicktags' => true
            )); ?>
            <p class="description"><?php _e('Add SEO content for this product category.', 'textdomain'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row"><label for="open_type"><?php _e('Open Type', 'textdomain'); ?></label></th>
        <td>
            <input type="checkbox" name="open_type" id="open_type" value="1" <?php checked($open_type, 1); ?>>
            <p class="description"><?php _e('Check if this category is open type.', 'textdomain'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="term_order"><?php _e('Sort Order', 'textdomain'); ?></label></th>
        <td>
            <input type="number" name="term_order" id="term_order" value="<?php echo esc_attr($term_order); ?>">
            <p class="description"><?php _e('Set a custom sorting order for categories.', 'textdomain'); ?></p>
        </td>
    </tr>
    <?php
}

add_action('product_category_edit_form_fields', 'edit_product_category_custom_fields', 10, 2);

// Save Custom Fields when Adding or Editing a Category
function save_product_category_custom_fields($term_id)
{
    if (isset($_POST['product_category_intro'])) {
        update_term_meta($term_id, 'product_category_intro', wp_kses_post($_POST['product_category_intro']));
    }

    if (isset($_POST['seo_content'])) {
        update_term_meta($term_id, 'seo_content', wp_kses_post($_POST['seo_content']));
    }

    if (isset($_POST['term_order']) && is_numeric($_POST['term_order'])) { // Ensure it's a number
        update_term_meta($term_id, 'term_order', intval($_POST['term_order']));
    } else {
        delete_term_meta($term_id, 'term_order'); // Prevent saving invalid values
    }

    $open_type = isset($_POST['open_type']) ? 1 : 0;
    update_term_meta($term_id, 'open_type', $open_type);
}

add_action('created_product_category', 'save_product_category_custom_fields', 10, 2);
add_action('edited_product_category', 'save_product_category_custom_fields', 10, 2);

function sort_product_categories_by_custom_order($query)
{
    if (!is_admin() && isset($query->query_vars['taxonomy']) && $query->query_vars['taxonomy'] === 'product_category') {
        $query->query_vars['orderby'] = 'meta_value_num';
        $query->query_vars['meta_key'] = 'term_order';
    }
}
add_action('pre_get_terms', 'sort_product_categories_by_custom_order');


function custom_redirect_product_category()
{
    if (is_tax('product_category')) {
        global $wp;
        $requested_path = $wp->request; // Get the requested URL path
        $url_parts = explode('/', trim($requested_path, '/')); // Split URL into parts

        // Reverse the array to check the deepest category first
        $url_parts = array_reverse($url_parts);

        foreach ($url_parts as $slug) {
            // Check if the slug exists as a product_category term
            $term = get_term_by('slug', $slug, 'product_category');
            if ($term) {
                // Found the deepest valid category, exit the function (no need to redirect)
                return;
            }
        }

        // If no valid category found, redirect to the home page
        wp_redirect(home_url());
        exit;
    }
}
add_action('template_redirect', 'custom_redirect_product_category');


function custom_menu_classes($classes, $item, $args) {
    if (!isset($_SERVER['REQUEST_URI'])) {
        return $classes;
    }

    $current_url = untrailingslashit($_SERVER['REQUEST_URI']);
    $menu_item_url = untrailingslashit(parse_url($item->url, PHP_URL_PATH)); // Get clean path of menu item

    // Define base slugs that should retain the active class
    $menu_slugs = ['/products', '/blogs', '/news'];

    // Ensure this only applies to primary menu items
    if (!empty($menu_item_url) && in_array($menu_item_url, $menu_slugs)) {
        if ($menu_item_url === $current_url || strpos($current_url, $menu_item_url . '/') === 0) {
            $classes[] = 'current-menu-item';
        }
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'custom_menu_classes', 10, 3);
