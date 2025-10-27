<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'timeline' .
    (!empty($block['className']) ? ' ' . $block['className'] : '') .
    (!empty($block['align']) ? ' align' . $block['align'] : '');

?>

<?php if (have_rows('timeline_event')): ?>
<div class="<?= esc_attr($className) ?>">

    <?php while (have_rows('timeline_event')): the_row(); ?>
    <div class="timeline__event-block">

        <?php if ($date = get_sub_field('date')): ?>
        <div class="timeline__date">
            <div class="inner">
                <h2><?= esc_html($date) ?></h2>
                <?php
                $image = get_sub_field('featured_image');
                $size = 'full';
                if( $image ) {
                    echo wp_get_attachment_image( $image, $size );
                } ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (have_rows('events')): ?>
        <div class="timeline__events">
            <?php while (have_rows('events')): the_row(); ?>
                <div class="event">
                    <?php the_sub_field('event'); ?>
                </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>

        <span class="divider"></span>

    </div>
    <?php endwhile; ?>

</div>
<?php endif; ?>
