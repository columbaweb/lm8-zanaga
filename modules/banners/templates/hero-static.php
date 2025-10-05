<?php

$page_id = get_the_ID();
$banner_content = get_field('static_content', $page_id);
$banner_title = get_field('titles_in_banner', 'option') ?? null;

// Determine page title based on context
$page_title = match (true) {
    is_404() => 'Page Not Found',
    is_search() => '<span class="span-1">Search results for</span>  <span class="span-2 pink">' . get_search_query().'</span>',
    is_archive() => get_the_archive_title(),
    is_singular('team') => 'Team',
    is_singular('post') => 'News & Insights',
    default => get_the_title($page_id),
};

// Determine banner image data
$image_data = match (true) {
    is_404() => get_field('fourofour_banner_styles', 'option'),
    is_search() => get_field('search_banner_styles', 'option'),
    is_singular('post') => has_post_thumbnail() 
        ? ['image' => get_post_thumbnail_id($page_id)] 
        : get_field('search_banner_styles', 'option'),
    is_singular('team') => get_field('default_banner_styles', 'option'),
    !empty($banner_content['image']) => $banner_content,
    default => get_field('default_banner_styles', 'option')
};

// Fallback image if none found
$image_id = $image_data['image'] ?? get_field('default_banner_styles', 'option')['image'] ?? null;

$custom_css = $banner_content['banner_custom_css'] ?? null;
$title = $banner_content['title'] ?? '';
$subtitle = $banner_content['subtitle'] ?? '';
$iframe = $banner_content['iframe'] ?? '';
$buttons = $banner_content['buttons'] ?? '';


// Display banner content
$has_image = !empty($image_id);
$text_only_class = !$has_image ? ' text-only' : '';

// Display banner image
if ($image_id) {
    echo '<div class="hero-static__image">';
    echo wp_get_attachment_image($image_id, 'full', false, ['class' => 'banner-image']);
    echo '</div>';
}

if ($banner_title && !is_front_page()) {
    echo '<div class="hero-static__content' . esc_attr($text_only_class) . '">';
    echo '<div class="wrap">';
    if ($title) {
        echo '<h1 class="hero-image__title">' . wp_kses_post($title) . '</h1>';
    } else {
        echo '<h1 class="hero-image__title">' . $page_title . '</h1>';
    }

    if ($subtitle) {
        echo '<p class="hero-image__subtitle">' . wp_kses_post($subtitle) . '</p>';
    }

    if (is_singular('post')) {
        if (empty($external_link)) {
            $content = get_post_field('post_content', get_the_ID());
            $word_count = str_word_count(strip_tags($content));
            $minutes = ceil($word_count / 200);
            $read_time = $minutes . ' min read';
        }

        echo '<p class="hero-image__subtitle">' 
            . esc_html(get_the_time('j F Y')) 
            . ($read_time ? ' <span class="read-time">' . esc_html($read_time) . '</span>' : '') 
            . '</p>';
    
        $tags = get_the_tags();
        if ($tags) {
            echo '<ul class="post-tags">';
            foreach ($tags as $tag) {
                echo '<li class="post-tag">' . esc_html($tag->name) . '</li>';
            }
            echo '</ul>';
        }
    }

    if( $buttons ) {
        echo '<ul class="hero__buttons">';
        foreach( $buttons as $button ) {
            $link =  $button['button'];
            $link_url = $link['url'];
            $link_title = $link['title'];
            $link_target = $link['target'] ? $link['target'] : '_self';
            echo '<li>';
            echo '<a class="btn" href="'.$link_url.'" target="'.$link_target.'">'.$link_title.'</a>';
            echo '</li>';
        }
        echo '</ul>';
    }
    
    echo '</div>'; // wrap

    echo '</div>';
}

// Display front page banner content
if (is_front_page()) {
    echo '<div class="hero-static__content front">';
    echo '<div class="has-global-padding">';
    echo '<div class="wrap">';
    
    echo '<div class="content-inner">';
        if ($title) {
            echo '<h2 class="hero-image__title">' . wp_kses_post($title) . '</h2>';
        }
        if ($subtitle) {
            echo '<p class="hero-image__subtitle">' . wp_kses_post($subtitle) . '</p>';
        }
    
        if( $buttons ) {
            echo '<ul class="hero__buttons">';
            foreach( $buttons as $button ) {
                $link =  $button['button'];
                $link_url = $link['url'];
                $link_title = $link['title'];
                $link_target = $link['target'] ? $link['target'] : '_self';
                echo '<li>';
                echo '<a class="btn" href="'.$link_url.'" target="'.$link_target.'">'.$link_title.'</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
    echo '</div>';
    
    if ($iframe) {
        echo '<div class="content-iframe"><div class="inner">' . $iframe . '</div></div>';
    }
    
    echo '<svg id="scrolldown" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 62 62" fill="none"><path class="outline" stroke="#FF6139" d="M60.667 31v.378C60.462 47.59 47.257 60.672 31 60.672c-11.73 0-21.874-6.81-26.687-16.697h-.001a28.606 28.606 0 0 1-1.025-2.372 29.276 29.276 0 0 1-1.958-10.604c0-3.816.732-7.458 2.043-10.812v.001a29.706 29.706 0 0 1 6.655-10.165v-.001C15.394 4.65 22.806 1.328 31 1.328c.713 0 1.42.034 2.127.085h.001c.8.055 1.592.135 2.37.254h.003c.796.118 1.582.283 2.37.468a29.668 29.668 0 0 1 15.257 9.113l.38.434A29.523 29.523 0 0 1 60.668 31Z"/><path fill="#FF6139" d="M55.996 31c0-6.218-2.271-11.902-6.032-16.276A25.004 25.004 0 0 0 36.788 6.68a24.356 24.356 0 0 0-1.998-.395c-.659-.1-1.326-.167-1.998-.214A25.273 25.273 0 0 0 31 6c-6.905 0-13.15 2.8-17.671 7.326A24.97 24.97 0 0 0 6 31c0 3.216.588 6.159 1.65 8.934.26.68.545 1.347.864 1.998C12.57 50.262 21.115 56 31 56c13.805 0 25-11.197 25-25.004l-.004.004Z"/><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M25.824 36.912 30.854 42 36 36.912"/><path stroke="#fff" stroke-linecap="round" stroke-width="2" d="M30.912 41V21"/></svg>';
    
    echo '</div>'; // wrap
    echo '</div>'; // global padding
    echo '</div>'; // content
}