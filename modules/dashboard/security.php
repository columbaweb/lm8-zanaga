<?php
if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

// ------------------------------------------
// DISABLE JSON API
// ------------------------------------------
function lmn_remove_api() {
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
}
add_action('after_setup_theme', 'lmn_remove_api');

// ------------------------------------------
// REMOVE WORDPRESS VERSION
// ------------------------------------------
add_filter('the_generator', '__return_false');

// ------------------------------------------
// DISABLE PING BACK SCANNER AND XMLRPC
// ------------------------------------------
add_filter('wp_xmlrpc_server_class', '__return_false');
add_filter('xmlrpc_enabled', '__return_false');

// ------------------------------------------
// REMOVE UNNECESSARY HEADER INFORMATION
// ------------------------------------------
function lmn_remove_header_info() {
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}
add_action('init', 'lmn_remove_header_info');

// ------------------------------------------
// DISABLE AUTOMATIC UPDATE
// ------------------------------------------
add_filter('auto_update_plugin', '__return_false');
add_filter('auto_update_theme', '__return_false');

// ------------------------------------------
// DISABLE RSS FEEDS
// ------------------------------------------
function lmn_disable_feed() {
    wp_die(__('No feed available, please visit our <a href="' . esc_url(get_bloginfo('url')) . '">homepage</a>!'));
}
add_action('do_feed', 'lmn_disable_feed', 1);
add_action('do_feed_rdf', 'lmn_disable_feed', 1);
add_action('do_feed_rss', 'lmn_disable_feed', 1);
add_action('do_feed_rss2', 'lmn_disable_feed', 1);
add_action('do_feed_atom', 'lmn_disable_feed', 1);
add_action('do_feed_rss2_comments', 'lmn_disable_feed', 1);
add_action('do_feed_atom_comments', 'lmn_disable_feed', 1);

// ------------------------------------------
// DISABLE JSON REST API
// ------------------------------------------
add_filter('json_enabled', '__return_false');
add_filter('json_jsonp_enabled', '__return_false');

// ------------------------------------------
// REMOVE XPINGBACK
// ------------------------------------------
function lmn_remove_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'lmn_remove_x_pingback');

// ------------------------------------------
// DISABLE COMMENTS
// ------------------------------------------
function lmn_disable_comments_status() {
    return false;
}
add_filter('comments_open', 'lmn_disable_comments_status', 20, 2);
add_filter('pings_open', 'lmn_disable_comments_status', 20, 2);

// ------------------------------------------
// REMOVE COMMENT PAGE FROM WP DASHBOARD
// ------------------------------------------
function lmn_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'lmn_disable_comments_admin_menu');

// ------------------------------------------
// REMOVE VERSION NUMBER FROM SCRIPTS
// ------------------------------------------
function lmn_remove_wp_ver_css_js($src) {
    if (strpos($src, 'ver=') !== false) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'lmn_remove_wp_ver_css_js', 9999);
add_filter('script_loader_src', 'lmn_remove_wp_ver_css_js', 9999);

// ------------------------------------------
// PREVENT MULTISITE SIGNUP
// ------------------------------------------
function lmn_prevent_multisite_signup() {
    wp_redirect(site_url());
    exit;
}
add_action('signup_header', 'lmn_prevent_multisite_signup');

// ------------------------------------------
// CHANGE AUTHOR URL SLUG TO NICKNAME
// ------------------------------------------
function lmn_change_author($query_vars) {
    if (array_key_exists('author_name', $query_vars)) {
        global $wpdb;
        $author_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='nickname' AND meta_value = %s", $query_vars['author_name']));
        if ($author_id) {
            $query_vars['author'] = $author_id;
            unset($query_vars['author_name']);
        }
    }
    return $query_vars;
}
add_filter('request', 'lmn_change_author');

function lmn_change_author_link($link, $author_id, $author_nicename) {
    $author_nickname = get_user_meta($author_id, 'nickname', true);
    if ($author_nickname) {
        $link = str_replace($author_nicename, $author_nickname, $link);
    }
    return $link;
}
add_filter('author_link', 'lmn_change_author_link', 10, 3);

function lmn_set_user_nicename_to_nickname(&$errors, $update, &$user) {
    if (!empty($user->nickname)) {
        $user->user_nicename = sanitize_title($user->nickname, $user->display_name);
    }
}
add_action('user_profile_update_errors', 'lmn_set_user_nicename_to_nickname', 10, 3);

// ------------------------------------------
// DISABLE AUTHOR PAGE TO BLOCK ENUM SCANNING
// ------------------------------------------
if (!is_admin() && isset($_SERVER['QUERY_STRING'])) {
    // Default URL format
    if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) {
        die();
    }
    add_filter('redirect_canonical', 'lmn_check_enum', 10, 2);
}

function lmn_check_enum($redirect, $request) {
    // Permalink URL format
    if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) {
        die();
    }
    return $redirect;
}

function lmn_remove_author_info_from_oembed($data, $post, $width, $height) {
    unset($data['author_name']);
    unset($data['author_url']);
    return $data;
}
add_filter('oembed_response_data', 'lmn_remove_author_info_from_oembed', 10, 4);

function lmn_custom_oembed_data() {
    remove_action('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
    remove_filter('oembed_response_data', 'get_oembed_response_data_rich', 10, 4);
    remove_filter('oembed_response_data', 'get_oembed_response_data', 10, 4);
}
add_action('init', 'lmn_custom_oembed_data');

// ------------------------------------------
// DISABLE THEME FILE EDITOR
// ------------------------------------------
function lmn_disable_theme_file_editor() {
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}
add_action('init', 'lmn_disable_theme_file_editor');

// ------------------------------------------
// ALLOW SVG UPLOADS
// ------------------------------------------
function lmn_svg_sanitize($file) {
    $file_name = $file['name'];
    $file_type = $file['type'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    if ($file_ext === 'svg') {
        $file['type'] = 'image/svg+xml';
    }

    return $file;
}

function lmn_svg_mime_type($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'lmn_svg_mime_type');
add_filter('wp_handle_upload_prefilter', 'lmn_svg_sanitize');
