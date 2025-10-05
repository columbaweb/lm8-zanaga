<?php
    $id = 'block-' . $block['id'];
    $anchor = !empty($block['anchor']) ? $block['anchor'] : $id;

    $className = 'tab-group';
    $className .= !empty($block['className']) ? ' ' . $block['className'] : '';
    $className .= !empty($block['align']) ? ' align' . $block['align'] : '';

    $layout = get_field('tabs_layout');
    $mob = get_field('tabs_layout_mob');
    $className .= get_field('first_panel_open') ? ' first-active' : '';
?>

<div class="<?= $className; ?> <?= $layout; ?> <?= $mob; ?>">
	<?php $allowed_blocks = [ 'pro/tab-nav', 'pro/tab-panel']; ?>
	<InnerBlocks allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" / />
</div>

<?php if (is_admin()) { ?>
<style>

    .tab-group {
        borde1: 1px dashed #ccc;
    }
    .tab-group.vert .wp-block-pro-tab-nav {
        width: 100%;
    }
    .vert ul.tabs {
        width: 100%;
        flex-direction: row;
    }
    .vert ul.tabs li {
        width: auto;
    }
    .tab-group.vert > .acf-innerblocks-container .wp-block-pro-tab-panel {
        width: 100%;
    }
</style>
<?php } ?>