<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'timeline' .
    (!empty($block['className']) ? ' ' . $block['className'] : '') .
    (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$year = get_field('year');
$title = get_field('title');
$image = get_field('featured_image');

// Add has-img class if image exists
$col1_classes = 'col col-1' . (!empty($image) ? ' has-img' : '');
?>

<div class="<?= esc_attr($className); ?>">
    <div class="<?= esc_attr($col1_classes); ?>">
        <div class="inner">
            <?php if (!empty($year)): ?>
                <p class="year"><?= wp_kses_post($year); ?></p>
            <?php endif; ?>

            <?php if ( (!empty($title)) || (!empty($image)) ): ?>
            <div class="feat">
            <?php endif; ?>

            <?php if (!empty($title)): ?>
                <h3><?= wp_kses_post($title); ?></h3>
            <?php endif; ?>

            <?php if (!empty($image)): ?>
                <?= wp_get_attachment_image($image, 'full'); ?>
            <?php endif; ?>

            <?php if ( (!empty($title)) || (!empty($image)) ): ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col col-2">
        <InnerBlocks />
    </div>
</div>