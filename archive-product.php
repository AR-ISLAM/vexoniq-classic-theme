<?php get_header(); ?>

<?php
// Get the queried term (category or subcategory)
$current_term = get_queried_object();
$search_query = get_query_var('s') ? get_query_var('s') : '';

// Set page title
$page_title = !empty($search_query) ? "Search Results" : (single_term_title('', false) ?: 'All Products');
$page_description = "Explore More About Xinsheng Products";
$page_banner_image = "https://vexoniq.com/wp-content/uploads/2025/03/page-banner-narrowed-3.jpg";

include get_template_directory() . '/structure_components/banner_and_directory.php';

// Get current page number
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Default product query arguments
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 12,
    'paged' => $paged
);

// Initialize $category_id
$category_id = null;

// Check if a category or subcategory is selected
if (is_tax('product_category')) {
    $category = get_queried_object();
    $category_id = $category->term_id;

    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_category',
            'field' => 'id',
            'terms' => $category_id,
        ),
    );
}

// Execute the product query
$query = new WP_Query($args);
?>

<div class="container-lg mt-4">
    <?php
    if (is_tax('product_category')) {
        $category = get_queried_object();
        $category_id = $category->term_id;

        // Retrieve custom fields
        $seo_content = get_term_meta($category_id, 'seo_content', true);
        $open_type = get_term_meta($category_id, 'open_type', true);
        $product_category_intro = get_term_meta($category_id, 'product_category_intro', true);

        // Conditionally load template based on open_type
        if ($open_type == 1) {
            include get_template_directory() . '/structure_components/products_type.php';
        } else {
            include get_template_directory() . '/structure_components/browse_products.php';
        }
    } else {
        include get_template_directory() . '/structure_components/browse_products.php';
    }
    ?>

    <!-- Pagination -->
    <div class="pagination text-center mt-4">
        <?php
        echo paginate_links(array(
            'total' => $query->max_num_pages
        ));
        ?>
    </div>
</div>

<?php
wp_reset_postdata();
get_footer();
?>

<style>
    .category-list {
        list-style: none;
        padding: 0;
    }

    .category-list li {
        margin: 5px 0;
    }

    .subcategory-list {
        list-style: none;
        margin-left: 15px;
        padding: 0;
    }

    .subcategory-list li {
        margin: 3px 0;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 40px auto;
        padding: 10px 0;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 10px 15px;
        margin: 0 5px;
        font-size: 16px;
        color: #333;
        text-decoration: none;
        border: 1px solid #ddd;
        border-radius: 6px;
        transition: all 0.3s ease-in-out;
    }

    .pagination a:hover {
        background-color: #0073aa;
        color: #fff;
        border-color: #0073aa;
    }

    .pagination .current {
        background: #0073aa;
        color: #fff;
        font-weight: bold;
        border-color: #0073aa;
    }
</style>