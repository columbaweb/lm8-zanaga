<?php

// ------------------------------------------
// ADD EXTERNAL LINK METABOX
// ------------------------------------------
function add_external_link_meta_field() {
    add_meta_box(
        'external_link_meta_box',
        'External Link',
        'external_link_meta_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'add_external_link_meta_field' );

function external_link_meta_box_callback( $post ) {
    $external_link = get_post_meta( $post->ID, 'external_link', true );
    echo '<label for="external_link" class="screen-reader-text">External Link: </label>';
    echo '<input type="text" id="external_link" name="external_link" value="' . esc_url( $external_link ) . '" size="100" />';
}

function save_external_link_meta_field( $post_id ) {
    if ( array_key_exists( 'external_link', $_POST ) ) {
        update_post_meta(
            $post_id,
            'external_link',
            esc_url_raw( $_POST['external_link'] )
        );
    }
}
add_action( 'save_post', 'save_external_link_meta_field' );


// ------------------------------------------
// AJAX callback: load WP posts with filters
// ------------------------------------------
function load_wp_posts_callback() {
    $offset   = isset($_POST['offset'])         ? intval($_POST['offset']) : 0;
    $per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 12;
    $category = isset($_POST['category'])       ? sanitize_text_field($_POST['category']) : '';
    $tag      = isset($_POST['tag'])            ? sanitize_text_field($_POST['tag']) : '';
    $year     = isset($_POST['year'])           ? intval($_POST['year']) : '';
    $search   = !empty($_POST['search'])        ? sanitize_text_field($_POST['search']) : '';

    $tag_post_ids = [];

    // step 1: find posts with tag name matching search term
    if ($search) {
        $matching_tags = get_terms([
            'taxonomy'   => 'post_tag',
            'hide_empty' => false,
            'name__like' => $search,
        ]);

        if (!is_wp_error($matching_tags) && $matching_tags) {
            $tag_ids = wp_list_pluck($matching_tags, 'term_id');

            $tag_posts = get_posts([
                'post_type'   => 'post',
                'post_status' => 'publish',
                'fields'      => 'ids',
                'numberposts' => -1,
                'tax_query'   => [[
                    'taxonomy' => 'post_tag',
                    'field'    => 'term_id',
                    'terms'    => $tag_ids,
                ]],
            ]);

            $tag_post_ids = $tag_posts;
        }
    }

    // step 2: build main query args
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'offset'         => $offset,
    ];

    if ($category) {
        $args['category_name'] = $category;
    }

    if ($tag) {
        $args['tag'] = $tag;
    }

    if ($year) {
        $args['year'] = $year;
    }

    if ($search) {
        $content_ids = get_posts([
            'post_type'   => 'post',
            'post_status' => 'publish',
            'fields'      => 'ids',
            's'           => $search,
            'numberposts' => -1,
        ]);
    
        $merged_ids = array_unique(array_merge($content_ids, $tag_post_ids));
    
        // If no matches, return immediately with 0 posts
        if (empty($merged_ids)) {
            wp_send_json([
                'posts' => [],
                'total' => 0,
            ]);
        }
    
        $args['post__in'] = $merged_ids;
    }

    // step 4: run query and output posts
    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('/parts/part', 'excerpt'); // Adjust this to match your template
            $posts[] = [
                'id'      => get_the_ID(),
                'content' => ob_get_clean(),
            ];
        }
    }

    wp_reset_postdata();

    //wp_send_json(['posts' => $posts]);
    wp_send_json([
        'posts' => $posts,
        'total' => $query->found_posts,
    ]);
}
add_action('wp_ajax_load_wp_posts', 'load_wp_posts_callback');
add_action('wp_ajax_nopriv_load_wp_posts', 'load_wp_posts_callback');