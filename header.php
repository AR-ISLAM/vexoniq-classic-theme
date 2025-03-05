<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="dashicons-div">
            <a href="https://www.facebook.com/" target="_blank"><span class="dashicons dashicons-facebook"></span></a>
            <a href="https://www.instagram.com/" target="_blank"><span class="dashicons dashicons-instagram"></span></a>
            <a href="https://www.youtube.com/" target="_blank"><span class="dashicons dashicons-youtube"></span></a>
            <a href="https://www.linkedin.com/" target="_blank"><span class="dashicons dashicons-linkedin"></span></a>
            <a href="https://twitter.com/" target="_blank"><span class="dashicons dashicons-twitter"></span></a>
        </div>
        <div class="contact-div">
            <a href="tel:+8618861255128" target="_blank">
                <span class="dashicons dashicons-phone"></span>
                <span>+86-188-6125-5128(Domestic)</span>
            </a>
            <a href="tel:+8618761175658" target="_blank">
                <span>+86-187-6117-5658(Overseas)</span>
            </a>
            <a href="mailto:sales@xinsheng-elec.com" target="_blank">
                <span class="dashicons dashicons-email"></span>
                <span>sales@xinsheng-elec.com</span>
            </a>
        </div>
    </div>
</div>

<!-- Site Header -->
<header class="site-header">
    <div class="container">
        <div class="header-inner">
            <!-- Left Side: Site Title/Logo -->
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="https://pub-0d1c3940f3f441d19d7ce01674e7a8b3.r2.dev/vexoniq_logo_20.png" alt="Vexoniq Logo">
                </a>
            </div>

            <!-- Right Side: Navigation Menu -->
            <nav class="main-nav">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'header-menu',
                    'container'      => false,
                    'menu_class'     => 'nav-menu',
                ));
                ?>
            </nav>
        </div>
    </div>
</header>


