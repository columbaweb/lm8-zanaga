<?php
if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly


// ------------------------------------------
// LOCAL JSON for ACF
// ------------------------------------------
// Define a custom save point for ACF JSON files
function lmn_acf_json_save_point($path) {
    $path = get_stylesheet_directory() . '/acf-json';
    return $path;
}
add_filter('acf/settings/save_json', 'lmn_acf_json_save_point');

// Define custom load points for ACF JSON files
function lmn_acf_json_load_point($paths) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'lmn_acf_json_load_point');


// ------------------------------------------
// MISC: DYNAMICALLY ADD WIDGETS TO ACF SIDEBAR SELECT FIELD
// ------------------------------------------
function lmn_acf_load_widgets_to_display_field_choices($field) {
    $field['choices'] = array('none' => 'None'); // Add 'None' option

    $widget_areas = wp_get_sidebars_widgets();

    if (!empty($widget_areas)) {
        foreach ($widget_areas as $widget_area => $widget_ids) {
            if ($widget_area !== 'wp_inactive_widgets') {
                $field['choices'][$widget_area] = $widget_area;
            }
        }
    }

    return $field;
}
add_filter('acf/load_field/name=widgets_to_display', 'lmn_acf_load_widgets_to_display_field_choices');