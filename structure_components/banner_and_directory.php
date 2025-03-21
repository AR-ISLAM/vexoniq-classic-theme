<div class="bannercrumbs">
    <div class="page-header">
        <img src="<?php echo isset($page_banner_image) ? $page_banner_image : 'default-image.jpg'; ?>" alt="Page Banner"
            class="header-image">
        <div class="page-header-content">
            <h3 class="display-5 text-white fw-bold mt-5">
                <?php echo isset($page_title) ? $page_title : get_the_title(); ?>
            </h3>
            <p class="lead fw-normal text-white mt-4"><?php echo isset($page_description) ? $page_description : ''; ?>
            </p>
        </div>
    </div>

    <div class="breadcrumbs-container">
        <div class="breadcrumbs container-lg">
            <span class="me-2">You are here: </span>
            <a class="me-1" href="<?php echo home_url(); ?>">
                <span class="dashicons dashicons-admin-home"></span> Home
            </a>

            <?php

            // Show "Products" only if the current page is not exactly "/products"
            if ($_SERVER['REQUEST_URI'] !== '/products' && $_SERVER['REQUEST_URI'] !== '/products/') {
                echo ' » <a href="' . esc_url(site_url('/products/')) . '">Products</a>';
            }


            // Check if a search query is present
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search_text = esc_html($_GET['search']); // Sanitize search query
                echo ' » <span>Search Results</span>';
            }
            // Check if filtering by category and series
            elseif (isset($_GET['category']) || isset($_GET['types']) || isset($_GET['series'])) {
                if (!empty($_GET['category'])) {
                    $category_slug = sanitize_text_field($_GET['category']);
                    $category = get_term_by('slug', $category_slug, 'product_category');
                    if ($category) {
                        echo ' » <a href="' . get_term_link($category) . '">' . esc_html($category->name) . '</a>';
                    }
                }

                if (!empty($_GET['types'])) {
                    $types_slug = sanitize_text_field($_GET['types']);
                    echo ' » <span>' . ucwords(str_replace('-', ' ', $types_slug)) . '</span>';
                }

                if (!empty($_GET['series'])) {
                    $series_slug = sanitize_text_field($_GET['series']);
                    echo ' » <span>' . ucwords(str_replace('-', ' ', $series_slug)) . '</span>';
                }
            }
            // If on product archive page (all products)
            elseif (is_post_type_archive('product')) {
                echo ' » <span>Products</span>';
            }
            // If viewing a single product
            elseif (is_singular('product')) {
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
            }
            ?>
        </div>
    </div>
</div>