<div class="bannercrumbs">
    <div class="page-header">
        <img src="<?php echo isset($page_banner_image) ? $page_banner_image : 'default-image.jpg'; ?>" alt="Page Banner" class="header-image">
        <div class="page-header-content">
            <h3 class="display-5 text-white fw-bold mt-5">
                <?php echo isset($page_title) ? $page_title : get_the_title(); ?>
            </h3>
            <p class="lead fw-normal text-white mt-4"><?php echo isset($page_description) ? $page_description : ''; ?></p>
        </div>
    </div>

    <div class="breadcrumbs-container">
        <div class="breadcrumbs container-lg">
            <span class="me-2">You are here: </span>
            <a class="me-1" href="<?php echo home_url(); ?>">
                <span class="dashicons dashicons-admin-home"></span> Home
            </a>

            <?php
            if (is_post_type_archive('product')) {
                // Just show "Products" without a link
                echo ' » <span>Products</span>';
            } else {
                // Always show "Products" as a clickable link unless already on the archive page
                echo ' » <a href="' . esc_url(site_url('/products/')) . '">Products</a>';
            }

            if (is_tax('product_category')) {
                // Show category hierarchy
                $term = get_queried_object();
                $ancestors = get_ancestors($term->term_id, 'product_category');

                if (!empty($ancestors)) {
                    foreach (array_reverse($ancestors) as $ancestor_id) {
                        $ancestor = get_term($ancestor_id, 'product_category');
                        echo ' » <a href="' . get_term_link($ancestor) . '">' . $ancestor->name . '</a>';
                    }
                }

                echo ' » <span>' . $term->name . '</span>';
            } elseif (is_singular('product')) {
                // Show product category path
                $terms = get_the_terms(get_the_ID(), 'product_category');
                if ($terms && !is_wp_error($terms)) {
                    $primary_term = array_shift($terms);
                    $ancestors = get_ancestors($primary_term->term_id, 'product_category');

                    if (!empty($ancestors)) {
                        foreach (array_reverse($ancestors) as $ancestor_id) {
                            $ancestor = get_term($ancestor_id, 'product_category');
                            echo ' » <a href="' . get_term_link($ancestor) . '">' . $ancestor->name . '</a>';
                        }
                    }

                    echo ' » <a href="' . get_term_link($primary_term) . '">' . $primary_term->name . '</a>';
                }

                echo ' » <span>' . get_the_title() . '</span>';
            } elseif (is_page() && !is_front_page()) {
                // Handle page hierarchy
                $post_ancestors = array_reverse(get_post_ancestors(get_the_ID()));
                foreach ($post_ancestors as $ancestor) {
                    echo ' » <a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a>';
                }
                echo ' » <span>' . get_the_title() . '</span>';
            }
            ?>
        </div>
    </div>
</div>
