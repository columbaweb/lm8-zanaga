<?php
$anchor = $block['anchor'] ?? 'block-' . $block['id'];
$className = trim('aim' . (isset($block['className']) ? " {$block['className']}" : '') . (isset($block['align']) ? " align{$block['align']}" : ''));

$link = get_field('link');
$link_url = $link['url'] ?? '';
$link_target = !empty($link['target']) && $link['target'] === '_blank' ? '_blank' : '_self'; // Corrected line
?>

<div class="<?= esc_attr($className); ?>">
    <div class="aim__title">
        <h3><?= esc_html(get_field('title')) ?></h3>
    </div>

    <div class="aim__rule">
        <?= get_field('content') ?>

        <?php if ($link): ?>
            <a class="aim__btn" 
               href="<?= esc_url($link_url) ?>" 
               target="<?= esc_attr($link_target) ?>">
                <?= esc_html($link['title'] ?? 'Read More') ?>
            </a>
        <?php endif; ?>
    </div>
</div>