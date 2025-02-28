<?php get_header() ?>

<div class="container">
    <div class="content-wrapper">
        
        <!-- Main Content: Post Boxes -->
        <div class="main-content">
            <?php while (have_posts()) { 
                the_post(); ?>
                <div class="post-box">
                    <a href="<?php the_permalink(); ?>" class="full-link"></a> <!-- Clickable Box -->
                    <div class="post-meta">
                        <span class="post-date"><?php echo get_the_date(); ?></span> | 
                        <span class="post-author"><?php the_author(); ?></span>
                    </div>
                    <h2 class="post-title"><?php the_title(); ?></h2>
                    <p class="post-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                </div>
            <?php } ?>
        </div>

        <!-- Sidebar: Categories -->
        <aside class="sidebar">
            <h3>Categories</h3>
            <ul class="category-list">
                <?php 
                $categories = get_categories();
                foreach ($categories as $category) {
                    echo '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . ' (' . $category->count . ')</a></li>';
                }
                ?>
            </ul>
        </aside>

    </div>
</div>





<?php get_footer() ?>