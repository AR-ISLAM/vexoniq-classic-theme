<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <link rel="icon" type="image/ico" href="<?php echo get_template_directory_uri(); ?>/assets/favicon.ico">
    <title>
        <?php
        if (is_singular('product')) {
            the_title(); // Show product name for single product pages
            echo ' | XINSHENG Electronics Co., LTD';
        } else {
            echo 'XINSHENG Electronics Co., LTD';
        }
        ?>
    </title>

</head>

<body <?php body_class(); ?>>

    <?php include get_template_directory() . '/structure_components/topbar.php'; ?>
    <?php include get_template_directory() . '/structure_components/floating_contact.php'; ?>
    <?php include get_template_directory() . '/structure_components/navbar.php'; ?>
