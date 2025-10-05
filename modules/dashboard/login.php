<?php
if (!defined('ABSPATH')) {
    exit;
}

// ------------------------------------------
// LOGIN SCREEN LOGO LINK
// ------------------------------------------
function lmn_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'lmn_login_logo_url');

// ------------------------------------------
// LOGIN SCREEN LOGO ALT TAG
// ------------------------------------------
function lmn_login_logo_url_title() {
    return esc_html(get_bloginfo('name'));
}
add_filter('login_headertext', 'lmn_login_logo_url_title');

// ------------------------------------------
// CUSTOM LOGIN SCREEN
// ------------------------------------------
function lmn_login_logo() { ?>
    <style type="text/css">
        body.login.login-action-login div#login h1 a,
        body.login.login-action-lostpassword div#login h1 a,
        body.login-action-checkemail div#login h1 a {
            <?php $login_logo = get_stylesheet_directory_uri() . '/assets/images/theme/logo.svg'; ?>
            background-image: url('<?= esc_url($login_logo); ?>');
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'lmn_login_logo');

// ------------------------------------------
// REMOVE LANGUAGE DROPDOWN ON ADMIN LOGIN
// ------------------------------------------
add_filter('login_display_language_dropdown', '__return_false');

// ------------------------------------------
// CHANGE LOGIN ERROR
// ------------------------------------------
function lmn_hide_login_errors() {
    return 'Wrong username and/or password.';
}
add_filter('login_errors', 'lmn_hide_login_errors');

// -------------------------------------------------
//  ADD WP CSS VARIABLES TO LOGIN PAGE
// -------------------------------------------------
function lmn_generate_css_from_theme_json() {
    $theme_json_path = get_stylesheet_directory() . '/theme.json';

    if (!file_exists($theme_json_path)) {
        return '';
    }

    $theme_json = json_decode(file_get_contents($theme_json_path), true);

    if (!$theme_json) {
        return '';
    }

    $css_variables = '';

    // COLOUR PALETTE
    if (isset($theme_json['settings']['color']['palette'])) {
        foreach ($theme_json['settings']['color']['palette'] as $color) {
            $css_variables .= "--wp--preset--color--{$color['slug']}: {$color['color']};\n";
        }
    }

    // TYPOGRAPHY
    if (isset($theme_json['settings']['typography']['fontFamilies'])) {
        foreach ($theme_json['settings']['typography']['fontFamilies'] as $font) {
            $css_variables .= "--wp--preset--font-family--{$font['slug']}: {$font['fontFamily']};\n";
        }
    }

    // FONT SIZES
    if (isset($theme_json['settings']['typography']['fontSizes'])) {
        foreach ($theme_json['settings']['typography']['fontSizes'] as $size) {
            $css_variables .= "--wp--preset--font-size--{$size['slug']}: {$size['size']};\n";
        }
    }

    // SPACING
    if (isset($theme_json['settings']['spacing']['spacingSizes'])) {
        foreach ($theme_json['settings']['spacing']['spacingSizes'] as $spacing) {
            $css_variables .= "--wp--preset--spacing--{$spacing['slug']}: {$spacing['size']};\n";
        }
    }

    return ":root {\n" . $css_variables . "}\n";
}

function lmn_enqueue_dynamic_login_styles() {
    $css = lmn_generate_css_from_theme_json();
    if (!empty($css)) {
        wp_add_inline_style('login', $css);
    }
}
add_action('login_enqueue_scripts', 'lmn_enqueue_dynamic_login_styles');
