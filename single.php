<?php 
    get_header(); 
    $sidebar = get_field('sidebar', 'option');
    $sidebar_position = $sidebar['sidebar_position'];
    $external_link = get_post_meta(get_the_ID(), 'external_link', true);
?>

<div class="has-sidebar sidebar-<?= esc_attr($sidebar_position); ?>">
    <div class="wrap">
            
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div id="content">
                <h2 class="main-heading"><?php the_title(); ?></h2>
        
                <?php the_content(); ?>
        
                <?php if (!empty($external_link)) : ?>
                    <?php
                        $is_interview = has_category('interviews', get_the_ID());
                        $button_text = $is_interview ? __('Watch video', 'lmn') : __('View article', 'lmn');
                    ?>
                    <a href="<?= esc_url($external_link); ?>" class="btn" target="_blank">
                        <strong><?= esc_html($button_text); ?></strong>
                    </a>
                <?php endif; ?>
            </div>			
        <?php endwhile; endif; ?>
        
        <?php get_sidebar(); ?>

    </div>
</div>

<?php get_footer(); ?>
