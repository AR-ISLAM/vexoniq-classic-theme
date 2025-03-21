<?php get_header(); ?>

<?php 
$page_title = get_the_title();
$page_description = get_the_excerpt();
$page_banner_image = "https://vexoniq.com/wp-content/uploads/2025/03/page-banner-narrowed-3.jpg";
include get_template_directory() . '/structure_components/banner_and_directory.php'; ?>
<div class="container">
    <?php include get_template_directory() . '/structure_components/maintenance.php'; ?>
</div>

</div>

<?php get_footer(); ?>
