<?php get_header(); ?>

<?php
// Extract the current queried term
$current_term = get_queried_object();
$search_query = get_query_var('s') ? get_query_var('s') : '';

// Determine page title
if (!empty($search_query)) {
    $page_title = "Search Results";
} else {
    $page_title = single_term_title('', false) ?: 'All Products';
}
$page_description = "Explore More About Xinsheng Products";
$page_banner_image = "https://vexoniq.com/wp-content/uploads/2025/03/page-banner-narrowed-3.jpg";

include get_template_directory() . '/structure_components/banner_and_directory.php';

// Check if we are in a product category taxonomy
// if (is_tax('product_category')) {
//     // Check if this category has a parent (i.e., it's a subcategory)
//     if ($current_term->parent) {
//         include get_template_directory() . '/structure_components/products_type.php';
//     } else {
//         include get_template_directory() . '/structure_components/browse_products.php';
//     }
// } else {
//     // If not a category, load the default browse products template
//     include get_template_directory() . '/structure_components/browse_products.php';
// }




if (is_tax('product_category')) {  // Ensure correct taxonomy name
    $category = get_queried_object(); // Get current category object
    $category_id = $category->term_id; // Get category ID

    // Retrieve custom fields
    $seo_content = get_term_meta($category_id, 'seo_content', true);
    $open_type = get_term_meta($category_id, 'open_type', true);
    $product_category_intro = get_term_meta($category_id, 'product_category_intro', true);


    if ($open_type == 1) {
        echo "I am OK"; // It's a child category
    } else {
        echo "I am NOT OK!"; // It's a parent category
    }
} else {
    echo "I am NOT OK!"; // Not a product category page
}


?>

<div class="container">

    <div class="content-wrapper">
        <div class="main-content">
            <?php
            // Pagination settings
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $posts_per_page = 10;

            // Get the search query (if any)
            $search_query = get_query_var('s') ? get_query_var('s') : '';

            // Query products
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'orderby' => 'title',
                'order' => 'ASC',
                's' => $search_query, // Add search query support
            );

            // If a specific product category is selected, filter the query
            if (is_tax('product_category')) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_category',
                        'field' => 'slug',
                        'terms' => $current_term->slug,
                    ),
                );
            }

            // Run WP_Query
            $products = new WP_Query($args);

            if ($products->have_posts()) {
                while ($products->have_posts()) {
                    $products->the_post();
                    ?>
                    <div class="post-box">
                        <a href="<?php the_permalink(); ?>" class="full-link"></a>
                        <h2 class="post-title"><?php the_title(); ?></h2>
                        <p class="post-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                    </div>
                    <?php
                }

                // Pagination
                ?>
                <div class="pagination">
                    <?php
                    echo paginate_links(array(
                        'total' => $products->max_num_pages,
                        'current' => $paged,
                        'format' => '?paged=%#%',
                        'prev_text' => '« Prev',
                        'next_text' => 'Next »',
                    ));
                    ?>
                </div>
                <?php
            } else {
                echo "<p>No products found.</p>";
            }
            wp_reset_postdata();
            ?>
        </div>

        <aside class="sidebar">
            <h3>Product Categories</h3>
            <ul class="category-list">
                <?php
                // Get all product categories and display hierarchy
                $args = array(
                    'taxonomy' => 'product_category',
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => false,
                );
                $categories = get_terms($args);

                function display_category_tree($parent_id = 0, $categories, $level = 0)
                {
                    foreach ($categories as $category) {
                        if ($category->parent == $parent_id) {
                            $padding = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                            echo '<li><a href="' . get_term_link($category) . '">' . $padding . $category->name . '</a></li>';
                            display_category_tree($category->term_id, $categories, $level + 1);
                        }
                    }
                }
                display_category_tree(0, $categories);
                ?>
            </ul>
        </aside>
    </div>
</div>

<?php get_footer(); ?>

<style>
    .category-list {
        list-style: none;
        padding: 0;
    }

    .category-list li {
        margin: 5px 0;
    }

    .pagination {
        margin-top: 20px;
        text-align: center;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 8px 12px;
        margin: 0 4px;
        background: #f0f0f0;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
    }

    .pagination .current {
        background: #0073aa;
        color: #fff;
        font-weight: bold;
    }
</style>