<?php 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Ensure ACF is installed before proceeding
if (!class_exists('ACF')) {
    return;
}

$modules = get_field('modules', 'option') ?? [];

// Helper function to register post types
function register_custom_post_type($type, $name, $singular_name, $menu_position, $icon, $supports = ['title'], $publicly_queryable = false, $has_archive = false, $show_in_nav_menus = true, $exclude_from_search = true) {
    $labels = [
        'name'                  => _x($name, 'post type general name'),
        'singular_name'         => _x($singular_name, 'post type singular name'),
        'menu_name'             => _x($name, 'admin menu'),
        'add_new'               => _x('Add New', 'document'),
        'add_new_item'          => __('Add New ' . $singular_name),
        'edit_item'             => __('Edit ' . $singular_name),
        'new_item'              => __('New ' . $singular_name),
        'view_item'             => __('View ' . $singular_name),
        'search_items'          => __('Search ' . $name),
        'not_found'             => __('No ' . strtolower($name) . ' found.'),
        'not_found_in_trash'    => __('No ' . strtolower($name) . ' found in Trash.'),
        'parent_item_colon'     => '',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => $publicly_queryable,
        'exclude_from_search'=> true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => strtolower($type)],
        'capability_type'    => 'post',
        'has_archive'        => $has_archive,
        'hierarchical'       => false,
        'menu_position'      => $menu_position,
        'menu_icon'          => $icon,
        'supports'           => $supports,
        'show_in_rest'       => true,
        'show_in_nav_menus'  => $show_in_nav_menus
    ];

    register_post_type($type, $args);
}

// Helper function to register taxonomies
function register_custom_taxonomy($type, $name, $object_type) {
    $labels = [
        'name'              => _x($name, 'taxonomy general name'),
        'singular_name'     => _x($name, 'taxonomy singular name'),
        'search_items'      => __('Search ' . $name),
        'all_items'         => __('All ' . $name),
        'parent_item'       => __('Parent ' . $name),
        'parent_item_colon' => __('Parent ' . $name . ':'),
        'edit_item'         => __('Edit ' . $name),
        'update_item'       => __('Update ' . $name),
        'add_new_item'      => __('Add New ' . $name),
        'new_item_name'     => __('New ' . $name . ' Name'),
        'menu_name'         => $name,
    ];

    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'       => true,
        'show_in_nav_menus'  => false
    ];

    register_taxonomy($type, $object_type, $args);
}

// Loop through each module and activate based on ACF configuration
foreach ($modules as $module => $enabled) {
    if ($enabled) {
        switch ($module) {
            case 'documents':
                add_action('init', function() {
                    register_custom_post_type('documents', 'Documents', 'Document', 5, 'dashicons-media-document', ['title'], true, false, false, false);
                    register_custom_taxonomy('document-type', 'Document Types', ['documents']);
                });
                break;
            case 'team':
                add_action('init', function() {
                    register_custom_post_type('team', 'Team', 'Person', 10, 'dashicons-groups', ['title', 'editor', 'thumbnail'], false, false, false, false);
                    register_custom_taxonomy('team-category', 'Team Categories', ['team']);
                });
                break;
            case 'careers':
                add_action('init', function() {
                    register_custom_post_type('careers', 'Careers', 'Vacancy', 20, 'dashicons-id', ['title', 'editor'], false, false, false, true);
                });
                break;
            case 'case_studies':
                add_action('init', function() {
                    register_custom_post_type('case-studies', 'Case Studies', 'Case Study', 20, 'dashicons-portfolio', ['title', 'editor', 'thumbnail'], true, true, false, true);
                    register_custom_taxonomy('case-study-category', 'Case Study Categories', ['case-studies']);
                });
                break;
            case 'testimonials':
                add_action('init', function() {
                    register_custom_post_type('testimonials', 'Testimonials', 'Testimonial', 29, 'dashicons-format-quote', ['title', 'editor'], false, false, false, true);
                });
                break;
            case 'calendar':
                add_action('init', function() {
                    register_custom_post_type('calendar', 'Calendar', 'Event', 5, 'dashicons-calendar', ['title'], false, false, false, true);
                });
                break;
        }
    }
}


// for facets
add_filter( 'register_post_type_args', function( $args, $post_type ) {
    if ( 'documents' === $post_type ) { 
      $args['exclude_from_search'] = false;
    }
    return $args;
  }, 10, 2 );


// use CPT title for documents file name and url
function handle_document_requests() {
    if (trim($_SERVER['REQUEST_URI'], '/') === 'documents') {
        wp_redirect(home_url(), 301);
        exit;
    }

    if (is_singular('documents')) {
        $file = get_field('file');

        if ($file) {
            $file_url = esc_url_raw($file);
            $file_type = wp_check_filetype($file_url);
            $mime_type = $file_type['type'] ?: 'application/pdf';
            $filename = sanitize_title_with_dashes(get_the_title()) . '.' . pathinfo($file_url, PATHINFO_EXTENSION);

            // Clear output buffering
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Disable gzip compression if enabled
            if (function_exists('apache_setenv')) {
                @apache_setenv('no-gzip', '1');
            }
            @ini_set('zlib.output_compression', 'Off');

            // Set headers
            header('Content-Type: ' . $mime_type);
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            header('Cache-Control: private, max-age=86400');

            // Open stream and pass through
            $stream = @fopen($file_url, 'rb');
            if ($stream) {
                fpassthru($stream);
                fclose($stream);
                exit;
            } else {
                wp_die('Unable to read the file stream.');
            }
        }
    }
}
add_action('template_redirect', 'handle_document_requests');

/*
function handle_document_requests() {
    // Redirect /documents/ to homepage
    if (trim($_SERVER['REQUEST_URI'], '/') === 'documents') {
        wp_redirect(home_url(), 301);
        exit;
    }

    // Stream file for individual documents
    if (is_singular('documents')) {
        $file = get_field('file');

        if ($file) {
            // Get file details
            $file_url = esc_url($file);
            $file_type = wp_check_filetype($file_url);
            $mime_type = $file_type['type'] ?: 'application/octet-stream';

            // Set headers to force inline display
            header('Content-Type: ' . $mime_type);
            header('Content-Disposition: inline; filename="' . basename($file_url) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            header('Cache-Control: private, max-age=86400');

            // Read and output the file
            readfile($file_url);
            exit;
        }
    }
}
add_action('template_redirect', 'handle_document_requests');
*/