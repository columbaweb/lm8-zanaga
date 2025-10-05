<?php
if (!defined('ABSPATH')) {
    exit;
}

// Load theme textdomain
load_theme_textdomain('lmn');

// ------------------------------------------
// THEME SUPPORT
// ------------------------------------------
if (function_exists('add_theme_support')) {
    // Add theme support
    add_theme_support('menus');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');

    // Add custom image sizes
    add_image_size('feat', 700, 440, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Top menu', 'lmn')
    ));
}
// ------------------------------------------
// REGISTER SIDE BARS
// ------------------------------------------
function lmn_create_sidebar($name, $id, $description = '', $class = 'widget') {
    register_sidebar(array(
        'name'          => __($name, 'lmn'),
        'id'            => $id,
        'description'   => __($description, 'lmn'),
        'before_widget' => '<div id="%1$s" class="' . esc_attr($class) . ' %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget--title">',
        'after_title'   => '</h3>',
    ));
}

function lmn_register_sidebars() {
    // Check if ACF is active
    if (function_exists('get_field')) {
        // Create the main sidebar
        lmn_create_sidebar('Sidebar', 'sidebar', 'Main sidebar', 'widget');

        // Declare the number of footer widgets, with a default value of 4
        $options = get_field('footer', 'option');
        $footer_widgets = isset($options['footer_column_count']) ? $options['footer_column_count'] : 4;

        // Declare the number of topbar widgets, with a default value of 0
        $header_options = get_field('header', 'option');
        $enable_topbar = isset($header_options['enable_topbar']) ? $header_options['enable_topbar'] : false;
        $topbar_widgets = isset($header_options['topbar_column_count']) ? $header_options['topbar_column_count'] : 0;

        // Loop to create topbar widgets
        if ($enable_topbar) {
            for ($j = 1; $j <= $topbar_widgets; $j++) {
                lmn_create_sidebar("Topbar widget $j", "topbar-$j", '', 'topbar-widget');
            }
        }

        // Loop to create footer widgets
        for ($i = 1; $i <= $footer_widgets; $i++) {
            lmn_create_sidebar("Footer sidebar $i", "footer-$i", '', 'footer-widget');
        }

        lmn_create_sidebar('Legal', 'legal', '', 'widget');
        //lmn_create_sidebar('Sub footer 2', 'subfooter-2', '', 'widget');

    } else {
        // Fallback behavior if ACF is not active
        lmn_create_sidebar('Sidebar', 'sidebar', 'Main sidebar', 'widget');

        // Default number of footer widgets
        for ($i = 1; $i <= 4; $i++) {
            lmn_create_sidebar("Footer sidebar $i", "footer-$i", '', 'footer-widget');
        }
    }
}
add_action('widgets_init', 'lmn_register_sidebars');

// ------------------------------------------
// REMOVE JQUERY MIGRATE
// ------------------------------------------
function lmn_remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'lmn_remove_jquery_migrate');

// ------------------------------------------
// REPLACE HTML TAGS WITH HTML5
// ------------------------------------------
add_action('template_redirect', function() {
    ob_start(function($buffer) {
        $buffer = str_replace(array('type="text/javascript"', "type='text/javascript'"), '', $buffer);
        $buffer = str_replace(array('type="text/css"', "type='text/css'"), '', $buffer);
        $buffer = str_replace(array('frameborder="0"', "frameborder='0'"), '', $buffer);
        $buffer = str_replace(array('scrolling="no"', "scrolling='no'"), '', $buffer);
        return $buffer;
    });
});

// ------------------------------------------
// W3C: global styles
// ------------------------------------------
add_action('wp_footer', function () {
    wp_dequeue_style('core-block-supports');
});

// ------------------------------------------
// W3C: Trailing Slash on Void Elements
// ------------------------------------------
function fix_trailing_slash($content) {
    if (!is_admin()) {
        $pattern = '/<(img|meta|link|input|br)\b([^>]*)\/>/i';
        $content = preg_replace($pattern, '<$1$2>', $content);
    }
    return $content;
}

function apply_fix_trailing_slash() {
    ob_start('fix_trailing_slash');
}
add_action('wp_head', 'apply_fix_trailing_slash', 1);
add_action('wp_footer', 'apply_fix_trailing_slash', 1);
add_filter('the_content', 'fix_trailing_slash', 11);

// ------------------------------------------
// W3C: Remove role="button"
// ------------------------------------------
function remove_button_role_attribute($content) {
    $pattern = '/<button\b(.*?)role=["\']button["\'](.*?)>/i';
    $content = preg_replace($pattern, '<button$1$2>', $content);
    return $content;
}

function remove_button_role_from_sections() {
    ob_start('remove_button_role_attribute');
}
add_action('wp_head', 'remove_button_role_from_sections', 1);
add_action('wp_footer', 'remove_button_role_from_sections', 1);
add_filter('the_content', 'remove_button_role_attribute', 11);


// ------------------------------------------
// W3C: WP 6.7.1 image fix
// ------------------------------------------
add_filter('wp_img_tag_add_auto_sizes', '__return_false');


// ------------------------------------------
// REMOVE WP-LOGO AND COMMENT ICON FROM ADMIN BAR
// ------------------------------------------
function lmn_remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'lmn_remove_admin_bar_links');

// ------------------------------------------
// ADD CUSTOM MESSAGE TO WP DASHBOARD
// ------------------------------------------
function lmn_dashboard_msg() {
    global $wp_meta_boxes;
    wp_add_dashboard_widget('custom_help_widget', 'Luminate', 'lmn_admin_msg_text');
}

function lmn_admin_msg_text() {
    echo '<p>This custom theme was created by <a href="https://luminate.works">luminate</a>. If you need any support, please email us at <a href="mailto:hello@luminate.works" target="_blank">hello@luminate.works</a>.</p>';
}
add_action('wp_dashboard_setup', 'lmn_dashboard_msg');

// ------------------------------------------
// REMOVE WELCOME TO WP MESSAGE
// ------------------------------------------
remove_action('welcome_panel', 'wp_welcome_panel');

// ------------------------------------------
// CHANGE WP DASHBOARD FOOTER
// ------------------------------------------
function lmn_change_footer_admin() {
    echo 'Powered by <a href="http://wordpress.org">WordPress</a> | designed &amp; built by <a href="https://luminate.works">luminate</a>';
}
add_filter('admin_footer_text', 'lmn_change_footer_admin');

// ------------------------------------------
// REMOVE WIDGETS FROM DASHBOARD
// ------------------------------------------
function lmn_remove_dashboard_widgets() {
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'lmn_remove_dashboard_widgets');

// ------------------------------------------
// PROPER ob_end_flush() FOR ALL LEVELS
// ------------------------------------------
remove_action('shutdown', 'wp_ob_end_flush_all', 1);
add_action('shutdown', function() {
    while (@ob_end_flush());
});

// ------------------------------------------
// REMOVE CIORE BLOCKS INLINE STYLES
// ------------------------------------------
add_action('wp_footer', function () {
    wp_dequeue_style('core-block-supports');
});