<?php
get_header();
?>


<div id="front-page-carousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#front-page-carousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
    <button type="button" data-bs-target="#front-page-carousel" data-bs-slide-to="1"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block" src="https://vexoniq.com/wp-content/uploads/2025/03/home_banner_1.jpg" alt="First slide">
      <div class="carousel-caption custom-caption">
        <h3 class="display-5 text-white fw-bold">Connect With Precision, Create With Precision</h3>
        <p class="lead fw-normal text-white mt-4">Reliable Connecting is for better communication</p>
        <a href="#services" class="btn btn-success btn-lg mt-3">View More</a>
      </div>
    </div>
    <div class="carousel-item">
      <img class="d-block" src="https://vexoniq.com/wp-content/uploads/2025/03/home_banner_2.webp" alt="Second slide">
      <div class="carousel-caption custom-caption">
        <h3 class="display-5 text-white fw-bold">Connect With Precision, Create With Precision</h3>
        <p class="lead fw-normal text-white mt-4">Reliable Connecting is for better communication</p>
        <a href="#services" class="btn btn-success btn-lg mt-3">View More</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#front-page-carousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#front-page-carousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>



<div class="container">
    <h1>HomePage</h2>

    <div class="content-wrapper">
        
        <!-- Main Content: Post Boxes -->
        <section class="main-content">
            <?php while (have_posts()) { 
                the_post(); ?>
                <div class="post-box">
                    <a href="<?php the_permalink(); ?>" class="full-link"></a> <!-- Clickable Box -->
                    <div class="post-meta">
                        <span class="post-date"><?php echo get_the_date(); ?></span> | 
                        <span class="post-author"><?php the_author(); ?></span>
                    </div>
                    <h2 class="post-title"><?php the_title(); ?></h2>
                    <p class="post-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                </div>
            <?php } ?>
        </section>

        <!-- Sidebar: Categories -->
        <aside class="sidebar">
            <h3>Categories</h3>
            <ul class="category-list">
                <?php 
                $categories = get_categories();
                foreach ($categories as $category) {
                    echo '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . ' (' . $category->count . ')</a></li>';
                }
                ?>
            </ul>
        </aside>

    </div>
</div>

</div>

<?php get_footer(); ?>

<script>
jQuery(document).ready(function($) {
    $('.carousel').carousel({
        interval: 3000, // Change slide every 3 seconds
        pause: 'hover',
        wrap: true
    });
});
</script>