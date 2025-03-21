<?php if ($query->have_posts()): ?>
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

