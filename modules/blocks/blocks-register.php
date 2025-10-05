<?php

// REGISTER BLOCKS
function lmn_register_acf_blocks() {
    $dir = trailingslashit(__DIR__);
    $excluded_files = [
        'blocks-functions.php', 
        'blocks-register.php', 
        'js', 
        'scss'
    ];

    if (is_dir($dir)) {
        $folders = array_diff(scandir($dir), array('..', '.'));
        $folders = array_diff($folders, $excluded_files);
        $folders = array_filter($folders, function ($folder) use ($dir) {
            return is_dir($dir . $folder) && !preg_match('/^(\.|_)/', $folder);
        });

        foreach ($folders as $folder) {
            $block_path = $dir . $folder . '/block.json';
            if (file_exists($block_path)) {
                register_block_type($dir . $folder);
            }
        }
    }
}
add_action('acf/init', 'lmn_register_acf_blocks');

// REGISTER BLOCK STYLES
function lmn_register_block_styles() { 
    $block_path = get_stylesheet_directory() . '/modules/blocks/';
    $directories = glob($block_path . 'block-*', GLOB_ONLYDIR);

    $register_blocks = array_map(function($dir) {
        return str_replace('block-', '', basename($dir));
    }, $directories);

    forEach($register_blocks as $block) {
        $blockName = 'lmn/' . $block;

        $css_path = get_stylesheet_directory() . '/modules/blocks/block-' . $block . '/block-' . $block . '.css';
        $css_file = get_stylesheet_directory_uri() . '/modules/blocks/block-' . $block . '/block-' . $block . '.css';
        
        if(file_exists($css_path) && has_block($blockName)) {
            wp_enqueue_style('block-' . $block . '-css', $css_file, array(), '', 'all' );
        }
    }
}
add_action('enqueue_block_assets', 'lmn_register_block_styles');

// REGISTER BLOCK SCRIPTS
function lmn_register_block_scripts() {
    $block_path = get_stylesheet_directory() . '/modules/blocks/';
    $directories = glob($block_path . 'block-*', GLOB_ONLYDIR);

    $register_blocks = array_map(function($dir) {
        return str_replace('block-', '', basename($dir));
    }, $directories);

    forEach($register_blocks as $block) {
        $blockName = 'lmn/' . $block;

        $js_path = get_stylesheet_directory() . '/modules/blocks/block-' . $block . '/block-' . $block . '.js';
        $js_file = get_stylesheet_directory_uri() . '/modules/blocks/block-' . $block . '/block-' . $block . '.js';

        if(file_exists($js_path) && has_block($blockName)) {
            wp_enqueue_script('block-' . $block . '-js', $js_file, array(), '', true);
        }
    }
    
}
add_action('enqueue_block_assets', 'lmn_register_block_scripts');
