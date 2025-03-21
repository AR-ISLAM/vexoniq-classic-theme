<?php
// Changing the title placeholder text for the Product post type
add_action('init', 'create_product_taxonomies');
function change_product_title_placeholder($title) {
    $screen = get_current_screen();
    if ('product' == $screen->post_type) {
        $title = 'Add Product Name';
    }
    return $title;
}
add_filter('enter_title_here', 'change_product_title_placeholder');

// Adding custom meta boxes for the Product post type
function add_product_intro_meta_box() {
    add_meta_box(
        'product_intro_meta_box',   // Unique ID
        'Product Introduction',      // Meta Box Title
        'render_product_intro_meta_box',  // Callback Function
        'product',                   // Post Type (Make sure it's the correct post type)
        'normal',                     // Context (normal, side, or advanced)
        'high'                        // Priority
    );
    
    add_meta_box(
        'product_short_title_meta_box', // Unique ID
        'Short Title',                 // Meta Box Title
        'render_product_short_title_meta_box', // Callback Function
        'product',                     // Post Type
        'normal',                       // Context
        'high'                          // Priority
    );
}
add_action('add_meta_boxes', 'add_product_intro_meta_box');

// Callback function to render the intro meta box
function render_product_intro_meta_box($post) {
    // Retrieve existing values if available
    $product_intro = get_post_meta($post->ID, 'product_intro', true);

    // Security nonce for data validation
    wp_nonce_field('save_product_intro_meta', 'product_intro_nonce');

    // Display the editor (TinyMCE)
    wp_editor($product_intro, 'product_intro', array(
        'textarea_name' => 'product_intro',
        'textarea_rows' => 8, // Adjust size
        'media_buttons' => false // Hide media upload button
    ));
}

// Callback function to render the short title meta box
function render_product_short_title_meta_box($post) {
    // Retrieve existing value
    $short_title = get_post_meta($post->ID, 'short_title', true);
    
    // Security nonce for data validation
    wp_nonce_field('save_product_short_title_meta', 'product_short_title_nonce');
    
    echo '<input type="text" name="short_title" value="' . esc_attr($short_title) . '" style="width:100%;" maxlength="100" placeholder="Enter short title (max 6 words)">';
}

// Save the meta box data
function save_product_meta_boxes_data($post_id) {
    // Check if nonce is set and valid
    if (!isset($_POST['product_intro_nonce']) || !wp_verify_nonce($_POST['product_intro_nonce'], 'save_product_intro_meta')) {
        return;
    }
    if (!isset($_POST['product_short_title_nonce']) || !wp_verify_nonce($_POST['product_short_title_nonce'], 'save_product_short_title_meta')) {
        return;
    }

    // Prevent autosave conflicts
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check if the user has permission to edit
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Sanitize and save the introduction input
    if (isset($_POST['product_intro'])) {
        update_post_meta($post_id, 'product_intro', wp_kses_post($_POST['product_intro']));
    }

    // Sanitize and save the short title input
    if (isset($_POST['short_title'])) {
        $short_title = sanitize_text_field($_POST['short_title']);
        
        // Limit to 6 words
        $words = explode(' ', $short_title);
        if (count($words) > 6) {
            $short_title = implode(' ', array_slice($words, 0, 6));
        }
        
        update_post_meta($post_id, 'short_title', $short_title);
    }
}
add_action('save_post', 'save_product_meta_boxes_data');
