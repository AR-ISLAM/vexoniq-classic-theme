<?php
get_header();
?>

<div class="breadcrumbs">
    <a href="<?php echo home_url(); ?>">Home</a> &gt; 
    <a href="<?php echo home_url('/products'); ?>">Products</a> &gt; 

    <?php
    // Check if we're on a product category page or a single product page
    if (is_tax('product_category')) {
        $term = get_queried_object();
        
        // Loop through all parent categories
        $parents = get_ancestors($term->term_id, 'product_category');
        $parents = array_reverse($parents); // Reverse to get the correct order

        foreach ($parents as $parent_id) {
            $parent_term = get_term($parent_id, 'product_category');
            echo '<a href="' . get_term_link($parent_term) . '">' . $parent_term->name . '</a> &gt; ';
        }

        // Display the current category
        echo '<a href="' . get_term_link($term) . '">' . $term->name . '</a> ';
    }

    // Check if we're on a single product page
    if (is_singular('product')) {
        // Get the categories assigned to the current product
        $terms = get_the_terms(get_the_ID(), 'product_category');
        if ($terms && !is_wp_error($terms)) {
            // Assuming the product has categories, we need to loop through them
            $term = array_pop($terms); // Get the first category (or any category)
            // Loop through the parent categories
            $parents = get_ancestors($term->term_id, 'product_category');
            $parents = array_reverse($parents);

            foreach ($parents as $parent_id) {
                $parent_term = get_term($parent_id, 'product_category');
                echo '<a href="' . get_term_link($parent_term) . '">>' . $parent_term->name . '</a> &gt; ';
            }

            echo '<a href="' . get_term_link($term) . '">>' . $term->name . '</a> ';
        }
    }
    ?>
</div>

<?php
if (have_posts()) :
    while (have_posts()) : the_post();
        // Product details
        the_title('<h1>', '</h1>');
        the_content();
    endwhile;
else :
    echo 'Product not found';
endif;

get_footer();
?>
