<?php if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

// ------------------------------------------
// ENQUEUE FRONT-END SCRIPTS AND STYLES
// ------------------------------------------
function enqueue_scripts_and_styles() {

    // Styles
    wp_enqueue_style('theme-styles', get_template_directory_uri() . '/scss/theme-styles.css');

    // WordPress jQuery
    wp_enqueue_script('jquery');

    // aline
    wp_enqueue_script( 'alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', array(), null, true );

    // gsap + animation.js - oprional
    wp_enqueue_script('gsap', get_template_directory_uri() . '/assets/packages/gsap/gsap.min.js', array(), '', false);
    wp_enqueue_script('gsap-scroll', get_template_directory_uri() . '/assets/packages/gsap/ScrollTrigger.min.js', array(), '', false);
    //wp_enqueue_script('gsap-draggable', get_template_directory_uri() . '/assets/packages/gsap/Draggable.min.js', array(), '', false);
    wp_enqueue_script('animation', get_template_directory_uri() . '/assets/js/animation.js', array(), null, true);
    wp_enqueue_script('tabs', get_template_directory_uri() . '/assets/js/tabs.js', array(), '', true);

    // swiper
    wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/packages/swiper/swiper-bundle.min.js', array(), '9.2.4', false);
    wp_enqueue_style('swiper', get_template_directory_uri() . '/assets/packages/swiper/swiper-bundle.min.css');

    // glightbox
    wp_enqueue_style('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css', [], null);
    wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', [], null, true);
    wp_add_inline_script('glightbox', 'document.addEventListener("DOMContentLoaded", function(){ GLightbox({ selector: ".glightbox" }); });');
    

    // banner
    wp_enqueue_script('hero', get_template_directory_uri() . '/modules/banners/js/hero.js', array(), null, true);
    
    wp_enqueue_script('theme', get_template_directory_uri() . '/assets/js/theme.js', array(), null, true);
}

// ------------------------------------------
// ENQUEUE ADMIN STYLES
// ------------------------------------------
function enqueue_editor_styles() {
    wp_enqueue_style('editor-styles', get_template_directory_uri() . '/scss/editor-styles.css');
}

// Hook to enqueue scripts and styles
add_action('wp_enqueue_scripts', 'enqueue_scripts_and_styles');

// Hook to enqueue editor styles
add_action('admin_enqueue_scripts', 'enqueue_editor_styles');

// Hook to enqueue scripts and styles on login page
add_action('login_enqueue_scripts', 'enqueue_scripts_and_styles', 10);