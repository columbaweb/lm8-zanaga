<?php

/**
 * Combine all .css files from /scss/core/ into one file
 * and enqueue it — only rewrite the file if contents have changed.
 */
function lmn_combine_and_enqueue_block_styles() {
    $dir = get_template_directory() . '/scss/core';
    $combined_css_file = get_template_directory() . '/scss/core-styles.css';
    $combined_css = '';

    // Check if directory exists
    if (is_dir($dir)) {
        $files = scandir($dir);

        foreach ($files as $file) {
            // Only process .css files
            if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
                $file_path = $dir . '/' . $file;
                if (is_readable($file_path)) {
                    $combined_css .= file_get_contents($file_path) . "\n";
                }
            }
        }

        // Check if combined CSS has changed before writing
        $existing_css = file_exists($combined_css_file) ? file_get_contents($combined_css_file) : '';
        if ($combined_css !== $existing_css) {
            file_put_contents($combined_css_file, $combined_css);
        }
    }

    // Enqueue the combined CSS file with cache busting
    if (file_exists($combined_css_file)) {
        $style_path = get_template_directory_uri() . '/scss/core-styles.css';
        wp_register_style('core-styles', $style_path, [], filemtime($combined_css_file));
        wp_enqueue_style('core-styles');
    }
}

add_action('enqueue_block_assets', 'lmn_combine_and_enqueue_block_styles');