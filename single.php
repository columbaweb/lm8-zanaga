<?php 
    get_header(); 
    $sidebar = get_field('sidebar', 'option');
    $sidebar_position = $sidebar['sidebar_position'];
    $external_link = get_post_meta(get_the_ID(), 'external_link', true);
?>

<div class="wrap has-sidebar sidebar-<?= esc_attr($sidebar_position); ?>">
    
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div id="content">
        <h2 class="main-heading"><?php the_title(); ?></h2>
        
        <p class="meta">
            <span class="category">
                <?php foreach (get_the_category() as $category): ?>
                    <?= esc_html($category->cat_name); ?> 
                <?php endforeach; ?>
            </span>
            <span class="date"><?= esc_html(get_the_time('d F Y')); ?></span>
        </p>

        <?php the_content(); ?>

        <?php if (!empty($external_link)) : ?>
            <a href="<?= esc_url($external_link); ?>" class="btn" target="_blank"><?= esc_html__('Read more', 'lmn'); ?></a>
        <?php endif; ?>
    </div>			
<?php endwhile; endif; ?>

<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
