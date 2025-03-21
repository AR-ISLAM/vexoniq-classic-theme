<!-- Site Navbar -->
<?php
// Function to add arrow icon to 'Products' menu item
function add_arrow_to_products_menu($items, $args)
{
    foreach ($items as &$item) {
        if ($item->title === 'Products') { // Check if menu item is "Products"
            $item->title .= ' <span class="dashicons dashicons-arrow-down"></span>';
            $item->classes[] = 'products_menu';
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'add_arrow_to_products_menu', 10, 2);
?>

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
                <ul class="nav-menu">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'header-menu',
                        'container' => false,
                        'items_wrap' => '%3$s', // Removes extra <ul> wrapper
                    ));
                    ?>
                    <!-- Search Icon (Same Level as Nav Items) -->
                    <li class="nav-item search-toggle" id="search-toggle">
                        <span class="dashicons dashicons-search"></span>
                    </li>
                </ul>
            </nav>

            <!-- Search Form (Initially Hidden) -->
            <div class="search-container" id="search-container">
                <button class="close-search" id="close-search">
                    <span class="dashicons dashicons-plus-alt2"></span>
                </button>
                <form action="<?php echo home_url('/products/'); ?>" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search products..." required>
                    <button type="submit">Search</button>
                </form>
            </div>
            <!-- Hamburger Menu (Mobile) -->
            <div class="menu-toggle" id="mobile-menu">
                ☰
            </div>
        </div>
    </div>
</header>

<!-- Products Category -->
<div class="products_category" id="products_category">
    <div class="container-lg">
        <div class="row">
            <?php
            $args = array(
                'taxonomy' => 'product_category',
                'parent' => 0,
                'hide_empty' => false,
                'orderby' => 'meta_value_num',
                'meta_key' => 'term_order',
                'order' => 'ASC',
            );
            $parent_categories = get_terms($args);

            if (!empty($parent_categories) && !is_wp_error($parent_categories)) {
                $counter = 0;

                foreach ($parent_categories as $parent_cat) {
                    $counter++;

                    // First row layout
                    if ($counter == 1) {
                        echo '<div class="col-md-8">'; // First category spans 2 columns (8/12)
                    } elseif ($counter == 2) {
                        echo '<div class="col-md-4">'; // Second category spans 1 column (4/12)
                    } elseif ($counter == 3) {
                        echo '</div><div class="row">'; // New row starts
                        echo '<div class="col-md-4">'; // Third category spans 1 column (4/12)
                    } else {
                        echo '<div class="col-md-4">'; // Fourth & Fifth category spans 1 column each
                    }
                    ?>

                    <div class="category-box p-3 border rounded">
                        <h3>
                            <a href="<?php echo esc_url(get_term_link($parent_cat)); ?>">
                                <?php echo esc_html($parent_cat->name); ?>
                            </a>
                        </h3>

                        <?php
                        // Get subcategories (sorted by most populated)
                        $sub_args = array(
                            'taxonomy' => 'product_category',
                            'parent' => $parent_cat->term_id,
                            'hide_empty' => false,
                            'orderby' => 'count', // Order by number of posts
                            'order' => 'DESC',  // Most populated first
                        );
                        $subcategories = get_terms($sub_args);

                        if (!empty($subcategories)) { ?>
                            <div class="subcategory-list">
                                <ul class="list-unstyled">
                                    <?php foreach ($subcategories as $sub_cat) {
                                        $custom_url = home_url("/products/" . $parent_cat->slug . "/" . $sub_cat->slug);
                                        ?>
                                        <li>
                                            <a href="<?php echo esc_url($custom_url); ?>">
                                                <?php echo esc_html($sub_cat->name); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div> <!-- End category-box -->

                </div> <!-- End col -->

            <?php }
                echo '</div>'; // Close second row
            } ?>
    </div> <!-- End Bootstrap row -->
</div>
</div>




<!-- Sidebar Menu (Mobile) -->
<div class="mobile-sidebar" id="sidebar">
    <div class="sidebar-content">
        <span class="close-btn" id="close-sidebar">&times;</span>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'header-menu',
            'container' => false,
            'menu_class' => 'mobile-nav-menu',
        ));
        ?>
        <form action="<?php echo home_url('/products/'); ?>" method="GET" class="sidebar-search-form">
            <input type="text" name="s" placeholder="Search products..." required>
            <button type="submit">Search</button>
        </form>


    </div>
</div>

<script>
    // Opening the Products products_category
    document.addEventListener("DOMContentLoaded", function () {
        const productsMenu = document.querySelector(".products_menu");
        const productsCategory = document.getElementById("products_category");

        if (productsMenu && productsCategory) {
            // Ensure the category is hidden on initial load
            productsCategory.style.display = "none";

            productsMenu.addEventListener("mouseenter", function () {
                productsCategory.style.display = "block";
            });

            productsMenu.addEventListener("mouseleave", function () {
                setTimeout(() => {
                    if (!productsCategory.matches(":hover")) {
                        productsCategory.style.display = "none";
                    }
                }, 5000);
            });

            productsCategory.addEventListener("mouseleave", function () {
                productsCategory.style.display = "none";
            });

            productsCategory.addEventListener("mouseenter", function () {
                productsCategory.style.display = "block";
            });
        }
    });

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

    document.addEventListener("DOMContentLoaded", function () {
        const searchToggle = document.getElementById("search-toggle");
        const searchContainer = document.getElementById("search-container");
        const closeSearch = document.getElementById("close-search");
        const navMenu = document.querySelector(".main-nav");

        searchToggle.addEventListener("click", function () {
            searchContainer.classList.toggle("active");
            navMenu.classList.toggle("hidden"); // Hide navigation when search is active
        });

        closeSearch.addEventListener("click", function () {
            searchContainer.classList.remove("active");
            navMenu.classList.remove("hidden"); // Show navigation when search is closed
        });
    });

</script>