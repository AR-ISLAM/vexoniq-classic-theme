<?php
// Adding the Product Gallery meta box
function add_product_gallery_meta_box() {
    add_meta_box(
        'product_gallery_meta_box',
        'Product Gallery',
        'render_product_gallery_meta_box',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_product_gallery_meta_box');

// Callback function to render the meta box
function render_product_gallery_meta_box($post) {
    $product_images = [
        'product_image_1' => get_post_meta($post->ID, 'product_image_1', true),
        'product_image_2' => get_post_meta($post->ID, 'product_image_2', true),
        'product_image_3' => get_post_meta($post->ID, 'product_image_3', true)
    ];
    
    wp_nonce_field('save_product_gallery_meta', 'product_gallery_nonce');
    ?>
    <style>
        .product-gallery-container {
            display: flex;
            gap: 10px;
        }
        .product-gallery-box {
            width: 100px;
            height: 100px;
            border: 2px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .product-gallery-box span {
            font-size: 24px;
            color: #aaa;
        }
        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: red;
            color: white;
            border: none;
            padding: 2px 5px;
            cursor: pointer;
            display: none;
        }
    </style>
    <div class="product-gallery-container">
        <?php foreach ($product_images as $key => $image) : ?>
            <div class="product-gallery-box" id="<?php echo $key; ?>" style="background-image: url('<?php echo esc_url($image); ?>');">
                <?php if (!$image) : ?>
                    <span>+</span>
                <?php endif; ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo esc_attr($image); ?>">
                <button class="remove-image" style="<?php echo $image ? 'display:block;' : ''; ?>">&times;</button>
            </div>
        <?php endforeach; ?>
    </div>
    
    <script>
        jQuery(document).ready(function($){
            $('.product-gallery-box').on('click', function(e) {
                e.preventDefault();
                var box = $(this);
                var input = box.find('input');
                var removeBtn = box.find('.remove-image');
                var mediaUploader = wp.media({
                    title: 'Choose Image',
                    button: { text: 'Choose Image' },
                    multiple: false
                }).on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    box.css('background-image', 'url(' + attachment.url + ')');
                    box.find('span').hide();
                    input.val(attachment.url);
                    removeBtn.show();
                }).open();
            });
            
            $('.remove-image').on('click', function(e) {
                e.stopPropagation();
                var box = $(this).closest('.product-gallery-box');
                box.css('background-image', '');
                box.find('span').show();
                box.find('input').val('');
                $(this).hide();
            });
        });
    </script>
    <?php
}

// Save the meta box data
function save_product_gallery_meta_box_data($post_id) {
    if (!isset($_POST['product_gallery_nonce']) || !wp_verify_nonce($_POST['product_gallery_nonce'], 'save_product_gallery_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = ['product_image_1', 'product_image_2', 'product_image_3'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, esc_url($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_product_gallery_meta_box_data');
?>