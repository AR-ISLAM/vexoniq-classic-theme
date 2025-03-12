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
            if (is_category() || is_tax()) {
                // Get current term
                $term = get_queried_object();
                $ancestors = get_ancestors($term->term_id, $term->taxonomy);

                // Display parent categories first
                if (!empty($ancestors)) {
                    foreach (array_reverse($ancestors) as $ancestor_id) {
                        $ancestor = get_term($ancestor_id, $term->taxonomy);
                        echo ' » <a href="' . get_term_link($ancestor) . '">' . $ancestor->name . '</a>';
                    }
                }

                // Display current category/subcategory
                echo ' » <span>' . $term->name . '</span>';
            } elseif (is_singular('post')) {
                // Show post category hierarchy
                $category = get_the_category();
                if ($category) {
                    $cat_tree = get_category_parents($category[0], true, ' » ');
                    echo rtrim($cat_tree, ' » ');
                }
            } elseif (is_post_type_archive()) {
                echo ' » <span>' . post_type_archive_title('', false) . '</span>';
            } elseif (is_page() && !is_front_page()) {
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
</div>