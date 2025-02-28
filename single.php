<?php get_header() ?>

<div class="container">
<?php
    while(have_posts()){
        the_post(); ?>
        <div class="post">
            <h2 class = "title"><?php the_title() ?></h2>
            <hr/>
            <div class = "content">
                <?php the_content() ?>
            </div>
        </div>
    <?php }
?>
</div>

<?php get_footer(); ?>