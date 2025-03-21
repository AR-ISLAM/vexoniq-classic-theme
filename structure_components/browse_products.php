<?php
// Fetch the top-level categories (parent categories)
$parent_categories = get_terms([
    'taxonomy' => 'product_category', // Using your custom taxonomy
    'hide_empty' => false,
    'parent' => 0, // Only top-level categories
]);

// Get selected category from the URL
$selected_category_slug = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
$selected_types_slug = isset($_GET['types']) ? sanitize_text_field($_GET['types']) : '';
$selected_series_slug = isset($_GET['series']) ? sanitize_text_field($_GET['series']) : '';
$search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

// Convert slugs to term IDs for querying
$selected_category = 0;
$selected_types = 0;
$selected_series = 0;

if ($selected_category_slug) {
    $category_term = get_term_by('slug', $selected_category_slug, 'product_category');
    if ($category_term && !is_wp_error($category_term)) {
        $selected_category = $category_term->term_id;
    }
}

if ($selected_types_slug) {
    $types_term = get_term_by('slug', $selected_types_slug, 'product_category');
    if ($types_term && !is_wp_error($types_term)) {
        $selected_types = $types_term->term_id;
    }
}

if ($selected_series_slug) {
    $series_term = get_term_by('slug', $selected_series_slug, 'product_category');
    if ($series_term && !is_wp_error($series_term)) {
        $selected_series = $series_term->term_id;
    }
}
?>

<!-- Search & Filter Container -->
<div class="product-search-container">
    <h2>Xinsheng Products Browser</h2>

    <!-- Search Form -->
    <form id="search-form" method="GET">
        <div class="search-box">
            <input type="text" name="search" id="search-input" placeholder="Search products..."
                value="<?php echo esc_attr($search_query); ?>">
            <button type="submit">Search</button>
        </div>
    </form>

    <!-- Category Filters -->
    <form id="category-filter" method="GET">
        <div class="dropdowns">
            <!-- Category Dropdown -->
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">Filter by Category</option>
                <?php foreach ($parent_categories as $parent_category): ?>
                    <option value="<?php echo esc_attr($parent_category->slug); ?>" <?php selected($selected_category_slug, $parent_category->slug); ?>>
                        <?php echo esc_html($parent_category->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Types Dropdown -->
            <?php if ($selected_category): ?>
                <?php
                $types_categories = get_terms([
                    'taxonomy' => 'product_category',
                    'hide_empty' => false,
                    'parent' => $selected_category,
                ]);
                ?>
                <select name="types" id="types" onchange="this.form.submit()">
                    <option value="">Select a Type</option>
                    <?php foreach ($types_categories as $types_category): ?>
                        <option value="<?php echo esc_attr($types_category->slug); ?>" <?php selected($selected_types_slug, $types_category->slug); ?>>
                            <?php echo esc_html($types_category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <!-- Series Dropdown -->
            <?php if ($selected_types): ?>
                <?php
                $series_categories = get_terms([
                    'taxonomy' => 'product_category',
                    'hide_empty' => false,
                    'parent' => $selected_types,
                ]);
                ?>
                <select name="series" id="series" onchange="this.form.submit()">
                    <option value="">Select a Series</option>
                    <?php foreach ($series_categories as $series_category): ?>
                        <option value="<?php echo esc_attr($series_category->slug); ?>" <?php selected($selected_series_slug, $series_category->slug); ?>>
                            <?php echo esc_html($series_category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
    </form>
</div>


<?php
// Fetch products based on the selected category or search query
$selected_term = $selected_series ? $selected_series : ($selected_types ? $selected_types : $selected_category);

$args = [
    'post_type' => 'product', // Replace with your custom post type if needed
    'posts_per_page' => 12, // Display 12 products per page
];

if ($selected_term) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'product_category', // Using your custom taxonomy
            'field' => 'term_id',
            'terms' => $selected_term,
        ],
    ];
}

if ($search_query) {
    $args['s'] = $search_query; // Add search query to the arguments
}

$query = new WP_Query($args);

if ($query->have_posts()): ?>
    <div class="product-grid">
        <?php while ($query->have_posts()):
            $query->the_post();
            $product_link = get_permalink();
            $product_title = get_post_meta(get_the_ID(), 'short_title', true);
            if (empty($product_title)) {
                $product_title = get_the_title(); // Fallback to full title if short title is empty
            }

            $product_excerpt = get_the_excerpt();
            $product_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            ?>

            <a href="<?php echo esc_url($product_link); ?>" class="product-card">
                <div class="product-image">
                    <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>">
                </div>
                <div class="product-card-text">
                    <h3 class="product-title"><?php echo esc_html($product_title); ?></h3>
                    <div class="divider"></div>
                    <p class="product-excerpt"><?php echo esc_html(wp_trim_words($product_excerpt, 15)); ?></p>
                </div>
            </a>

        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p class="no-products">No products found.</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoryDropdown = document.getElementById('category');
        const typesDropdown = document.getElementById('types');
        const seriesDropdown = document.getElementById('series');

        function toggleDropdownVisibility() {
            if (typesDropdown) {
                typesDropdown.style.display = typesDropdown.options.length > 1 ? 'block' : 'none';
            }
            if (seriesDropdown) {
                seriesDropdown.style.display = seriesDropdown.options.length > 1 ? 'block' : 'none';
            }
        }

        if (categoryDropdown) {
            categoryDropdown.addEventListener('change', function () {
                // Reset types and series dropdowns when category changes
                if (typesDropdown) {
                    typesDropdown.value = '';
                    typesDropdown.style.display = 'none'; // Hide until new options load
                }
                if (seriesDropdown) {
                    seriesDropdown.value = '';
                    seriesDropdown.style.display = 'none';
                }

                this.form.submit();
            });
        }

        if (typesDropdown) {
            typesDropdown.addEventListener('change', function () {
                if (seriesDropdown) {
                    seriesDropdown.value = '';
                    seriesDropdown.style.display = 'none';
                }
                this.form.submit();
            });
        }

        toggleDropdownVisibility();
    });

</script>