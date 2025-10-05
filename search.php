<?php get_header(); ?>

<div class="wrap full-width">
    <div id="content">
        <?php 
        $titles_in_banner = class_exists('ACF') ? get_field('titles_in_banner', 'option') : false;
        if (!$titles_in_banner): 
        ?>
            <h1 class="main-heading"><?php the_title(); ?></h1>
        <?php endif; ?>

        <?php if (have_posts()): ?>    
            <ul class="search-results">
                <?php while (have_posts()): the_post(); 
                    $title = esc_html(get_the_title());
                    $permalink = esc_url(get_permalink());
                    $excerpt = get_the_excerpt() ? wp_trim_words(get_the_excerpt(), 20, '...') : wp_trim_words(get_the_content(), 20, '...');
                ?>
                <li>
                    <a href="<?= $permalink; ?>" class="search-results__item" title="<?= $title; ?>">
                        <h2><?= $title; ?></h2>
                        <?php if ($excerpt): ?>
                            <p class="excerpt"><?= esc_html($excerpt); ?></p>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endwhile; ?>
            </ul>

            <?php 
            the_posts_pagination([
                'mid_size'  => 2,
                'prev_text' => __('Previous', 'textdomain'),
                'next_text' => __('Next', 'textdomain'),
            ]);
            ?>

        <?php else: ?>
            <h2 class="main-heading">Nothing found for <?= esc_html(get_search_query()); ?></h2>
            <p>Sorry, we couldn't find any results for "<strong><?= esc_html(get_search_query()); ?></strong>". Please try using different keywords or check the spelling.</p>

        <?php endif; ?>        
    </div>
</div>

<?php get_footer(); ?>
