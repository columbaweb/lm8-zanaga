<?php
// Get ACF fields
$title = get_field('title');
$heading = get_field('large_title');
$subtitle = get_field('subtitle');
$bg = get_field('background');
$link = get_field('link');
$text_only = get_field('text_only');

// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'link-cta' 
    . (!empty($block['className']) ? ' ' . $block['className'] : '') 
    . (!empty($block['align']) ? ' align' . $block['align'] : '') 
    . ($text_only ? ' text-link' : '');

// Determine if the link is a PDF
$is_pdf = $link && pathinfo($link['url'], PATHINFO_EXTENSION) === 'pdf';

// Determine if the link is internal or external
$is_external = isset($link['url']) && (strpos($link['url'], home_url()) === false);
$target_attr = $is_external ? '_blank' : '_self';
?>

<<?= $link ? 'a' : 'div' ?> class="<?= $className; ?> fade-in" 
    <?= $link ? 'href="' . esc_url($link['url']) . '" target="' . esc_attr($target_attr) . '" title="Go to ' . esc_attr($title) . '"' : '' ?>>
    
    <?php if ($bg) {
        echo '<span class="bg" style="background-image: url('.$bg.');"></span>';
    } ?>

<?php if (($title) || ($subtitle)) : ?>
    <div class="link-cta__title">
    <?php if ($title): ?>
        <h3 class="is-style-small-title">
            <?= esc_html($title); ?>
            <span><em></em><em></em><em></em></span>
        </h3>
    <?php endif; ?>

    <?php if ($heading): ?>
        <h2><?= esc_html($heading); ?></h2>
    <?php endif; ?>

    <?= $subtitle; ?>
    </div>
<?php endif; ?>

<?php if ($link): ?>
    <div class="link-cta__btn">
        <?php 
        if ($is_pdf): 
            echo file_get_contents(get_template_directory() . '/assets/images/theme/arrow-download.svg'); 
        else: 
            echo file_get_contents(get_template_directory() . '/assets/images/theme/arrow-right.svg'); 
        endif; 
        ?>
    </div>
<?php endif; ?>

</<?= $link ? 'a' : 'div' ?>>