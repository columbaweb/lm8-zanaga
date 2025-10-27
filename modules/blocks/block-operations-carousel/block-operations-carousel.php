<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'operations-carousel' . 
    (!empty($block['className']) ? ' ' . $block['className'] : '') . 
    (!empty($block['align']) ? ' align' . $block['align'] : '');

$total = have_rows('operations') ? count(get_field('operations')) : 0;

if ($total === 1) {
    $className .= ' single-slide';
}
?>

<?php if ($total): ?>
<div class="swiper <?= esc_attr($className); ?>">

    <div class="swiper-wrapper">
        <?php 
            while( have_rows('operations') ) : the_row(); 
            $title = get_sub_field('title');
            $subtitle = get_sub_field('subtitle');
            $image =  get_sub_field('featured_image');
            $size = 'full';

            $link = get_sub_field('button');
            if ($link) {
                $link_url = $link['url'] ?? '';
                $link_title = $link['title'] ?? 'Read more';
                $link_target = !empty($link['target']) ? $link['target'] : '_self';
            }
        ?>
            <div class="swiper-slide">

                <div class="slide-content">
                    
                    <div class="col-2 equal">
                        <?php 
                            if( $image ) {
                                echo wp_get_attachment_image( $image, $size );
                            }
                        ?>
                        
                        <div class="slider-controls">
                            <?php get_template_part('/parts/part', 'carousel-nav'); ?>
                        </div>
                    </div>

                    <div class="col-1 equal">
                        <div class="col-1-inner">
                            <?php if ($title) : ?>
                                <h2 class="wp-block-heading is-style-small-title"><?= $title; ?></h2>
                            <?php endif; ?>
                            
                            <?php if ($subtitle) : ?>
                                <h3><?= $subtitle; ?></h3>
                            <?php endif; ?>
                            
                            <?php the_sub_field('intro'); ?>
    
                            <?php if ($link) : ?>
                            <a class="btn" href="<?= esc_url($link_url) ?>" target="<?= esc_attr($link_target) ?>">
                                <strong><?= esc_html($link_title); ?></strong>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                
                
            </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>

</div>
<?php endif; ?>




