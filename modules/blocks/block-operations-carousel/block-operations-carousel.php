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
                <?php 
                    if( $image ) {
                        echo wp_get_attachment_image( $image, $size );
                    }
                ?>

                <div class="slide-content fade-in-left">

                    <div class="col-1">
                        <?php if ($title) : ?>
                            <h3><?= $title; ?></h3>
                        <?php endif; ?>

                        <?php if ($link) : ?>
                         <a class="btn" href="<?= esc_url($link_url) ?>" target="<?= esc_attr($link_target) ?>">
                            <?= esc_html($link_title); ?>
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="col-2">
                        <?php the_sub_field('intro'); ?>
                    </div>

                </div>
                
                
            </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <div class="slider-controls">
        <?php get_template_part('/parts/part', 'carousel-nav'); ?>
    </div>

</div>
<?php endif; ?>




