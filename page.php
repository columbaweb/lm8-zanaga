<?php 
    get_header(); 
    $sidebar = get_field('sidebar', 'option');
    $sidebar_position = $sidebar['sidebar_position'];
?>

<div class="has-sidebar sidebar-<?= esc_attr($sidebar_position); ?>">
    <div class="wrap">
        <div id="content">
            <?php 
            $titles_in_banner = class_exists('ACF') ? get_field('titles_in_banner', 'option') : false;
            if (! $titles_in_banner) : 
            ?>
                <h1 class="main-heading"><?php the_title(); ?></h1>
            <?php endif; ?>

            <?php 
                if (have_posts()) : while (have_posts()) : the_post();
                    the_content();
                endwhile; endif; 
            ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>