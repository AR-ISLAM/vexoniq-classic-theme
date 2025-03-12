<?php
/*
Template Name: About Us
*/
get_header(); ?>

<?php
$page_title = "About Xinsheng Electric";
$page_description = "Our History and Sucess!";
$page_banner_image = "https://vexoniq.com/wp-content/uploads/2025/03/page-banner-narrowed-4.jpg";
include get_template_directory() . '/structure_components/banner_and_directory.php';
?>

<div class="container-lg">

    <?php include get_template_directory() . '/structure_components/maintenance.php'; ?>
    <!-- <?php
    if (have_posts()):
        while (have_posts()):
            the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        <?php endwhile;
    endif;
    ?> -->
</div>

<?php get_footer(); ?>