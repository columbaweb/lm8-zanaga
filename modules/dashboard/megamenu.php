<?php

class Mega_Menu_Walker extends Walker_Nav_Menu {

    // Start Level
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $header_options = get_field('header', 'option');
        $enable_mega_menu = isset($header_options['enable_mega_menu']) && $header_options['enable_mega_menu'];

        if ($enable_mega_menu && $depth === 0) {
            $output .= '<div class="megamenu child-menu"><div class="wrap">';

            // Add the featured image if it exists
            if (!empty($this->current_item_featured_image)) {
                $output .= sprintf('<div class="mm-featured-image"><img src="%s" alt="%s"></div>', esc_url($this->current_item_featured_image), esc_attr($this->current_item_title));
            }

            // Add the description if it exists
            if (!empty($this->current_item_description)) {
                $output .= sprintf('<div class="mm-description"><p>%s</p></div>', esc_html($this->current_item_description));
            }
        }

        $output .= '<ul class="sub-menu' . (!$enable_mega_menu && $depth === 0 ? ' child-menu' : '') . '">';
    }

    // End Level
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $header_options = get_field('header', 'option');
        $enable_mega_menu = isset($header_options['enable_mega_menu']) && $header_options['enable_mega_menu'];

        if ($enable_mega_menu && $depth === 0) {
            $output .= '</ul></div></div>';
        } else {
            $output .= '</ul>';
        }
    }

    // Start Element
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $classes = implode(' ', $item->classes);

        // Open the <li> and <a> tags
        $output .= sprintf( '<li class="menu-item %s"><a href="%s">%s</a>',
            esc_attr($classes),
            esc_url( $item->url ),
            esc_html( $item->title )
        );

        // Add sub-toggle if the item has children
        if (in_array('menu-item-has-children', $item->classes)) {
            $output .= '<span class="sub-toggle"></span>';
        }

        // Store the description and featured image for use in start_lvl
        if ($depth === 0 && in_array('menu-item-has-children', $item->classes)) {
            // Fetch the featured image from the ACF field (assuming it's stored as an ID)
            $featured_image_id = get_field('featured_image', $item);
            if ($featured_image_id) {
                $this->current_item_featured_image = wp_get_attachment_url($featured_image_id);
            } else {
                $this->current_item_featured_image = '';
            }
            $this->current_item_description = $item->description;
            $this->current_item_title = $item->title; // Storing the title for alt text
        } else {
            $this->current_item_featured_image = '';
            $this->current_item_description = '';
            $this->current_item_title = '';
        }
    }

    // End Element
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= '</li>';
    }
}