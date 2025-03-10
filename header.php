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
            <a href="https://www.youtube.com/" target="_blank"><span class="dashicons dashicons-youtube"></span></a>
            <a href="https://www.linkedin.com/" target="_blank"><span class="dashicons dashicons-linkedin"></span></a>
        </div>
        <div class="location-translate-div">
            <div class="topbar-location" onclick="openModal()">
                <span class="dashicons dashicons-location"></span>
                <a class="location-text" href="javascript:void(0);" onclick="openModal()">Our Location</a>
            </div>
            <div class="language-switcher">
                <?php echo do_shortcode('[gtranslate]'); ?>
            </div>
        </div>
    </div>
</div>

<!-- Floating Contact Bar -->
<div class="floating-contact">
    <a href="tel:+8618861255128">
        <span class="dashicons dashicons-phone"></span>
        <span class="icon-number">1</span> <!-- Circle with number 1 -->
        <span class="contact-info">Domestic: +86-188-6125-5128</span>
    </a>
    <a href="tel:+8618761175658">
        <span class="dashicons dashicons-phone"></span>
        <span class="icon-number">2</span> <!-- Circle with number 2 -->
        <span class="contact-info">Overseas: +86-187-6117-5658</span>
    </a>
    <a href="https://api.whatsapp.com/send?phone=8618761175658" target="_blank">
        <span class="dashicons dashicons-whatsapp"></span>
        <span class="contact-info">WhatsApp: +86-187-6117-5658</span>
    </a>
    <a href="mailto:email@example.com">
        <span class="dashicons dashicons-email"></span>
        <span class="contact-info">sales@xinsheng-elec.com</span>
    </a>
</div>

<!-- Modal -->
<div id="location-modal" class="location-modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <!-- Embed Google Map in iframe -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3395.7997272487164!2d120.0573930750754!3d31.666698539898913!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x35b47156653ae199%3A0x306d44ee91c4a914!2z5bi45bee5biC5paw55ub6Zu75Zmo5pyJ6ZmQ5YWs5Y-4!5e0!3m2!1szh-TW!2stw!4v1741232550765!5m2!1szh-TW!2stw" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<!-- Site Navbar -->
<header class="site-navbar">
    <div class="container">
        <div class="navbar-inner">
            <!-- Left Side: Site Title/Logo -->
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo(); // WordPress allows logo upload
                    } else {
                        echo '<img src="' . get_template_directory_uri() . '/assets/website-logo.webp" alt="Xinsheng Electric Logo">';
                    }
                    ?>
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
            <!-- Hamburger Menu (Mobile) -->
            <div class="menu-toggle" id="mobile-menu">
                ☰
            </div>
            <form action="<?php echo home_url('/products/'); ?>" method="GET" class="search-form">
    <input type="text" name="s" placeholder="Search products..." required>
    <button type="submit">Search</button>
</form>

<style>
    .search-form {
        display: flex;
        gap: 5px;
    }

    .search-form input {
        padding: 8px;
        width: 200px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .search-form button {
        padding: 8px 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>
        </div>
    </div>
</header>

<!-- Sidebar Menu (Mobile) -->
<div class="mobile-sidebar" id="sidebar">
    <div class="sidebar-content">
        <span class="close-btn" id="close-sidebar">&times;</span>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'header-menu',
            'container'      => false,
            'menu_class'     => 'mobile-nav-menu',
        ));
        ?>
    </div>
</div>


<script>
function openModal() {
    document.getElementById("location-modal").style.display = "block";
}

function closeModal() {
    document.getElementById("location-modal").style.display = "none";
}

// Close the modal if the user clicks anywhere outside the modal content
window.onclick = function(event) {
    if (event.target == document.getElementById("location-modal")) {
        closeModal();
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const mobileMenu = document.getElementById("mobile-menu");
    const sidebar = document.getElementById("sidebar");
    const closeSidebar = document.getElementById("close-sidebar");

    // Open Sidebar
    mobileMenu.addEventListener("click", function () {
        sidebar.classList.add("active");
    });

    // Close Sidebar
    closeSidebar.addEventListener("click", function () {
        sidebar.classList.remove("active");
    });

    // Close Sidebar When Clicking Outside
    document.addEventListener("click", function (event) {
        if (!sidebar.contains(event.target) && !mobileMenu.contains(event.target)) {
            sidebar.classList.remove("active");
        }
    });
});

</script>
