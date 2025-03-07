<?php
// Hook to add custom meta box for Related Products
function add_related_products_meta_box() {
    add_meta_box(
        'related_products_meta_box',  // Unique ID for the meta box
        'Related Products',           // Meta box title
        'render_related_products_meta_box', // Callback to render the content of the meta box
        'product',                   // Post type
        'normal',                     // Positioning of the box (normal, side, advanced)
        'high'                        // Priority
    );
}
add_action('add_meta_boxes', 'add_related_products_meta_box');

// Callback to render the content of the Related Products meta box
function render_related_products_meta_box($post) {
    // Fetch existing related products (if any)
    $related_products = get_post_meta($post->ID, 'related_products', true);
    $related_products = $related_products ? explode(',', $related_products) : [];
    
    // Query all products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC'
    );
    $products_query = new WP_Query($args);

    // Security nonce for saving data
    wp_nonce_field('save_related_products_meta', 'related_products_nonce');

    // Array to store structured products
    $products_by_hierarchy = [];
    $processed_products = [];

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product_id = get_the_ID();
            $product_title = get_the_title();
            $categories = get_the_terms($product_id, 'product_category'); // Corrected taxonomy

            if ($categories && !is_wp_error($categories)) {
                // Find the deepest category path
                $deepest_category = null;
                $deepest_depth = -1;
                $category_paths = [];

                foreach ($categories as $category) {
                    $depth = 0;
                    $category_path = [];
                    $current_category = $category;

                    // Build category hierarchy path
                    while ($current_category) {
                        array_unshift($category_path, $current_category->name);
                        $current_category = $current_category->parent ? get_term($current_category->parent, 'product_category') : null;
                        $depth++;
                    }

                    // Store paths temporarily
                    $category_paths[$depth] = implode(' >> ', $category_path);

                    // Determine the deepest category
                    if ($depth > $deepest_depth) {
                        $deepest_depth = $depth;
                        $deepest_category = $category_paths[$depth];
                    }
                }

                // Assign product only under its deepest category path
                if ($deepest_category && !isset($processed_products[$product_id])) {
                    $products_by_hierarchy[$deepest_category][$product_id] = $product_title;
                    $processed_products[$product_id] = true; // Mark product as processed
                }
            } else {
                // Fallback if product has no category
                if (!isset($processed_products[$product_id])) {
                    $products_by_hierarchy['Uncategorized'][$product_id] = $product_title;
                    $processed_products[$product_id] = true;
                }
            }
        }
    }
    wp_reset_postdata();

    ?>

    <p><strong>Select Related Products:</strong></p>
    <select name="related_products[]" id="related_products" multiple style="width: 100%; height: 200px;">
        <?php
        if (!empty($products_by_hierarchy)) {
            foreach ($products_by_hierarchy as $hierarchy => $products) {
                echo "<optgroup label='$hierarchy'>";
                foreach ($products as $id => $title) {
                    $selected = in_array($id, $related_products) ? 'selected' : '';
                    echo "<option value='$id' $selected>$title</option>";
                }
                echo "</optgroup>";
            }
        } else {
            echo '<option value="">No products found</option>';
        }
        ?>
    </select>
    <small>Hold Ctrl (Windows) / Cmd (Mac) to select multiple products.</small>

    <?php
}


// Hook to save the selected Related Products
function save_related_products_meta_box_data($post_id) {
    // Check nonce for security
    if (!isset($_POST['related_products_nonce']) || !wp_verify_nonce($_POST['related_products_nonce'], 'save_related_products_meta')) {
        return;
    }

    // Prevent auto-save conflicts
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check for user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save related products as a comma-separated list of post IDs
    if (isset($_POST['related_products'])) {
        $related_product_ids = array_map('intval', $_POST['related_products']);
        update_post_meta($post_id, 'related_products', implode(',', $related_product_ids));
    } else {
        delete_post_meta($post_id, 'related_products');
    }
}
add_action('save_post', 'save_related_products_meta_box_data');

?>