<?php
// Set block ID and base class
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];

$className = 'plx'
    . (!empty($block['className']) ? ' ' . $block['className'] : '')
    . (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get ACF fields
$image = get_field('image');
$size = 'full';

$direction = get_field('scroll_direction');
$position = get_field('position');

// Append direction and position as plx- prefixed classes
if ($direction) {
    $className .= ' plx-' . sanitize_html_class($direction);
}
if ($position) {
    $className .= ' plx-' . sanitize_html_class($position);
}

if (is_admin()) {
    echo "<p><strong>Parallax background:</strong> click to edit<p>";
}
?>

<div class="plx-container">
    <div class="<?= esc_attr($className); ?>">
        <?php if ($image): ?>
            <?= wp_get_attachment_image($image, $size); ?>
        <?php endif; ?>
    </div>
</div>