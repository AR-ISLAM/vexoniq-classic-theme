<?php
/*
Template Name: Contact Us
*/
get_header(); ?>

<?php
$page_title = "Contact Us Today!";
$page_description = "";
$page_banner_image = "https://vexoniq.com/wp-content/uploads/2025/03/page-banner-narrowed-5.jpg";
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