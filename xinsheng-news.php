<?php
/*
Template Name: News Page
*/
get_header(); ?>

<main>
    <h1>News</h1>
    <?php
    $news_query = new WP_Query(['category_name' => 'news']);
    if ($news_query->have_posts()) :
        while ($news_query->have_posts()) : $news_query->the_post();
            the_title('<h2>', '</h2>');
            the_excerpt();
        endwhile;
    else :
        echo '<p>No news articles found.</p>';
    endif;
    ?>
</main>

<?php get_footer(); ?>
