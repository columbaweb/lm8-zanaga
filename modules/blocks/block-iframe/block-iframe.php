<?php
    // Set block ID and classes
    $id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
    $className = 'lmn-iframe' 
        . (!empty($block['className']) ? ' ' . $block['className'] : '') 
        . (!empty($block['align']) ? ' align' . $block['align'] : '');
        $cleanedClassName = trim(str_replace('lmn-iframe', '', $className));

    // Get ACF fields
    $url = get_field('url');
?>

<div class="<?= esc_attr($className); ?>">
    <iframe src="<?= esc_url($url); ?>" title="<?php bloginfo('name'); ?> <?= esc_attr($cleanedClassName); ?> tool"></iframe>
</div>

<?php if (is_admin()) { ?>
    <style>
        .lmn-iframe iframe {
            pointer-events: none;
        }
    </style>
<?php } ?>