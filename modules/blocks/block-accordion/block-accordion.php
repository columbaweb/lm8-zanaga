<?php
$className = 'accordion';
$className .= !empty($block['className']) ? ' ' . $block['className'] : '';
$className .= !empty($block['align']) ? ' align' . $block['align'] : '';
$title = get_field('title');
$sub = get_field('subtitle');
$icon = get_field('icon');
$size = 'full';
$display = get_field('display');
$uid = uniqid();
?>

<?php $is_editor = is_admin(); ?>

<div 
    <?= !$is_editor ? 'x-data="accordion" x-init="init"' : '' ?>
    class="<?= esc_attr($className); ?>" 
    data-accordion 
    <?= $display ? 'data-open="true"' : '' ?>
>
    <div 
        class="accordion__title" 
        data-trigger 
        <?= !$is_editor ? '@click="toggle($el)" :class="{ \'active\': open }"' : '' ?>
    >
        <?php if ($icon) : ?>
            <span class="icon"><?= wp_get_attachment_image($icon, $size); ?></span>
        <?php endif; ?>
        <h3 class="title">
			<span class="title-top"><?= $title; ?></span>
			<?php if ($sub) { ?>
			<span class="title-bottom"><?= $sub; ?></span>
			<?php } ?>
		</h3>
    </div>

    <div class="accordion__panel" data-panel>
        <div class="panel-inner">
			<InnerBlocks />
        </div>
    </div>
</div>