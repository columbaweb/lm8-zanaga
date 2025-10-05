<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'statistics' . 
    (!empty($block['className']) ? ' ' . $block['className'] : '') . 
    (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$counter = get_field('counter_number');
$counter_2 = get_field('counter_number_line_2');
$title = get_field('counter_title');
?>

<div class="<?= esc_attr($className) ?>">
    <p class="statistics__number"><?= $counter; ?></p>

    <?php if($counter_2) { ?>
    <p class="statistics__number line2"><?= $counter_2; ?></p>
    <?php } ?>
    <p class="statistics__title fade-in"><?= $title; ?></p>
</div>
