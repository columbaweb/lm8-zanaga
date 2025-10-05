<div class="tab-panel" id="<?php the_field('id'); ?>">
    
    <input id="p-<?php the_field('id'); ?>" type="checkbox" <?php if (is_admin()) { echo 'disabled'; } ?> />

    <label for="p-<?php the_field('id'); ?>" class="acc-title">
        <span><?php the_field('title'); ?></span>
    </label>

    <div class="acc-panel">
        <InnerBlocks />
    </div>
    
</div>

<?php if (is_admin()) { ?>
<style>
	.tab-panel {
        margin-bottom: 20px;
		border: 1px dashed #c0c1c5;
        position: relative;
	}
    .tab-panel > div {
        opacity: 1;
        padding: 20px 15px 5px 15px;
    }
    .tab-panel > label {
        display: block;
        font-size: 12px;
        background: #eee;
        padding: 1px 7px;
        position: absolute;
        top: 0;
        left: 0;
    }
    .tab-panel > label:before {
        content: 'Tab: ';
    }

    .vert .tab-panel {
        width: 100%;
    }
</style>
<?php } ?>

