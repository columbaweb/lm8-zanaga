<?php if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

# ------------------------------------------
# EXTEND ALL BLOCKS
# ------------------------------------------
function lmn_blocks_extend_enqueue() {
	wp_enqueue_script( 'blocks-extend',
	get_template_directory_uri() . '/modules/blocks/js/blocks-extend.js',
	array( 'wp-blocks', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-hooks' )
	);

}
add_action( 'enqueue_block_editor_assets', 'lmn_blocks_extend_enqueue' );

# ------------------------------------------
# REGISTER LUMINATE BLOCK CATEGORY
# ------------------------------------------
function lmn_block_category( $block_categories, $block_editor_context ) {
	return array_merge(
		$block_categories,
		array(
			array(
				'slug'  => 'luminate',
				'title' => __( 'luminate', 'lmn' ),
			),
		)
	);
}
add_filter( 'block_categories_all', 'lmn_block_category', 10, 2 );