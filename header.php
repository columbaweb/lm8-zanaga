<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>

<?php 
    $is_front = is_front_page(); 
    $enable_topbar = false;
    $enable_mega_menu = false;
    $enable_header_search = false;
    $banner_type = '';

    if (class_exists('ACF')) {
        $header_options = get_field('header', 'option');
        $enable_topbar = !empty($header_options['enable_topbar']);
        $enable_mega_menu = !empty($header_options['enable_mega_menu']);
        $enable_header_search = !empty($header_options['enable_header_search']);
        $banner_type = get_field('banner_type', get_the_ID());
    }

?>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

    <a href="#content" class="skip-link">Skip to content</a>

    <?php if ($enable_topbar): ?>
        <div id="topbar">
            <div class="wrap">
                <?php 
                if (isset($header_options['topbar_column_count'])) {
                    for ($i = 1; $i <= $header_options['topbar_column_count']; $i++) {
                        dynamic_sidebar("topbar-$i");
                    }
                }
                ?>
            </div>
        </div>
    <?php endif; ?>

    <header class="header has-global-padding">
        <div class="wrap">
            <div class="inner">
                <?php if ($is_front): ?>
                    <h1 class="branding">
                <?php else: ?>
                    <div class="branding">
                <?php endif; ?>
                
                <a href="<?= esc_url(home_url('/')); ?>" rel="home" title="<?php bloginfo('name'); ?>" aria-label="<?php bloginfo('name'); ?> homepage">
                    <?= file_get_contents(get_stylesheet_directory() . '/assets/images/theme/logo.svg'); ?>
                    <span class="screen-reader-text"><?php bloginfo('name'); ?></span>
                </a>
                
                <?php if ($is_front): ?>
                    </h1>
                <?php else: ?>
                    </div>
                <?php endif; ?>
    
                <?php if (has_nav_menu('primary')): ?>
                    <button id="nav-expander" class="nav-expander">
                        <span class="screen-reader-text">Menu</span>
                        <span class="bar"></span>
                    </button>
    
                    <nav id="sitenav" class="sitenav">
                        <?php
                        $menu_args = [
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => $enable_mega_menu ? 'menu has-megamenu' : 'menu',
                            'walker' => new Mega_Menu_Walker(),
                            'echo' => false,
                        ];
    
                        $menu_html = wp_nav_menu($menu_args);
    
                        // Append search <li> if enabled
                        if ($enable_header_search) {
                            $search_icon = file_get_contents(get_stylesheet_directory() . '/assets/images/theme/icon-search.svg');
    
                            $search_item = '
                            <li class="menu-item menu-item-search">
                                <button class="search-toggle" aria-label="Open search popup">
                                    <span class="screen-reader-text">Search</span>' .
                                    $search_icon .
                                '</button>
                            </li>';
                        
                            $menu_html = preg_replace('/<\/ul>\s*$/', $search_item . '</ul>', $menu_html);
                        }
    
                        echo $menu_html;
                        ?>
    
                        <?php if ($enable_header_search): ?>
                            <div id="header-search-popup" class="search-popup">
                                <button class="search-close" aria-label="Close search popup">&times;</button>
                                <div class="search-popup-inner">
                                    <h2>Search the site</h2>
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </nav>
    
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <?php
            if (is_404() || is_search() || is_archive() || is_single()) {
                $banner_type = 'static';
                $banner_template = 'modules/banners/templates/hero-static.php';
            } else {
                if (class_exists('ACF')) {
                    $banner_type = get_field('banner_type', get_the_ID());
                }

                if (!empty($banner_type) && $banner_type !== 'none') {
                    switch ($banner_type) {
                        case 'static':
                            $banner_template = 'modules/banners/templates/hero-static.php';
                            break;
                        case 'video':
                            $banner_template = 'modules/banners/templates/hero-video.php';
                            break;
                        case 'slideshow':
                            $banner_template = 'modules/banners/templates/hero-slideshow.php';
                            break;
                        case 'none':
                            $banner_template = 'modules/banners/templates/hero-title.php';
                            break;
                        default:
                            $banner_template = '';
                            break;
                    }
                }
            }

            if (!empty($banner_template) && file_exists(get_template_directory() . '/' . $banner_template)) {
                $hero_class = 'hero hero-' . $banner_type;
                if ($is_front) $hero_class .= ' home';
                echo '<div class="' . esc_attr($hero_class) . '">';
                require get_template_directory() . '/' . $banner_template;
                echo '</div>';
            } else {
                echo '<!-- Banner template not found or no banner displayed -->';
            }
        ?>