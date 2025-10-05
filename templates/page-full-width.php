<?php 
/* Template Name: Full Width Page */ 
    get_header(); 
?>

<div class="full-width">
    <div id="content">
        <?php 
        $titles_in_banner = class_exists('ACF') ? get_field('titles_in_banner', 'option') : false;
        if (! $titles_in_banner) : 
        ?>
            <h1 class="main-heading"><?php the_title(); ?></h1>
        <?php endif; ?>

        <?php 
        if (have_posts()) : 
            while (have_posts()) : the_post(); 
                the_content();
            endwhile; 
        endif; 
        ?>
    </div>
</div>

<?php get_footer(); ?>