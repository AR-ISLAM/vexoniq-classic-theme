<?php get_header(); ?>

<?php 
$page_title = preg_replace('/^[^:]+:\s*/', '', get_the_archive_title()); // Remove any text before ":"  
$page_description = "Explore More About Xinsheng Products";  
$page_banner_image = "https://vexoniq.com/wp-content/uploads/2025/03/page-banner-narrowed-3.jpg";  
include get_template_directory() . '/structure_components/banner_and_directory.php';  
?>

<div class="container">
<?php include get_template_directory() . '/structure_components/maintenance.php'; ?>

    <!-- <div class="content-wrapper">

        <div class="main-content">
            <?php
            // Check if there's a search query
            if (isset($_GET['s']) && !empty($_GET['s'])) {
                // Get the search query
                $search_query = sanitize_text_field($_GET['s']);

                // WP_Query to search products
                $args = array(
                    'post_type' => 'product', // Make sure 'product' is correct
                    'posts_per_page' => 20,
                    's' => $search_query, // The search term
                );

                // Execute the query
                $search_results = new WP_Query($args);

                echo "<h3>Search Results for: <em>" . esc_html($search_query) . "</em></h3>";

                if ($search_results->have_posts()) {
                    // Loop through the results
                    while ($search_results->have_posts()) {
                        $search_results->the_post();
                        ?>
                        <div class="post-box">
                            <a href="<?php the_permalink(); ?>" class="full-link"></a>
                            <h2 class="post-title"><?php the_title(); ?></h2>
                            <p class="post-date"><?php echo get_the_date(); ?></p>
                        </div>
                    <?php }
                } else {
                    echo "<p>No products found. Try searching with different keywords.</p>";
                }
                wp_reset_postdata(); // Reset the query data after custom query
            } else {
                // If no search query, show all products (or show some default content)
                while (have_posts()) {
                    the_post();
                    ?>
                    <div class="post-box">
                        <a href="<?php the_permalink(); ?>" class="full-link"></a>
                        <div class="post-meta">
                            <span class="post-date"><?php echo get_the_date(); ?></span> |
                            <span class="post-author"><?php the_author(); ?></span>
                        </div>
                        <h2 class="post-title"><?php the_title(); ?></h2>
                        <p class="post-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                    </div>
                <?php }
            }
            ?>
        </div>

        <aside class="sidebar">
            <h3>Product Categories</h3>
            <?php
            // Get product categories
            $args = array(
                'taxonomy' => 'product_category', // Make sure 'product_category' is correct
                'orderby' => 'name',
                'order' => 'ASC',
                'parent' => 0, // Top-level categories
                'hide_empty' => false,
            );
            $parent_categories = get_terms($args);

            foreach ($parent_categories as $parent) { ?>
                <div class="category-dropdown">
                    <select class="category-select" onchange="if (this.value) window.location.href=this.value;">
                        <option value="" disabled selected>
                            <?php echo $parent->name . ' (' . $parent->count . ')'; ?>
                        </option>
                        <option value="<?php echo get_term_link($parent); ?>">
                            → Go to <?php echo $parent->name; ?>
                        </option>

                        <?php
                        // Get child categories
                        $child_args = array(
                            'taxonomy' => 'product_category',
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'parent' => $parent->term_id,
                            'hide_empty' => false,
                        );

                        $child_categories = get_terms($child_args);

                        foreach ($child_categories as $child) { ?>
                            <option value="<?php echo get_term_link($child); ?>">
                                -- <?php echo $child->name . ' (' . $child->count . ')'; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>
        </aside>

    </div> -->
</div>

<?php get_footer(); ?>

<style>
    .category-dropdown {
        margin-bottom: 10px;
    }

    .category-select {
        width: 100%;
        padding: 8px;
        font-size: 16px;
    }
</style>