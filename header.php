<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="top-bar">
    <p>Welcome to Vexoniq Learning Hub! </p>
</div>
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


