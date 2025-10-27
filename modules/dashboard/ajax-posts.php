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
// AJAX callback: load WP posts (category only)
// ------------------------------------------
function load_wp_posts_callback() {
    $offset   = isset($_POST['offset'])         ? intval($_POST['offset']) : 0;
    $per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 12;
    $category = isset($_POST['category'])       ? sanitize_text_field($_POST['category']) : '';

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'offset'         => $offset,
    ];

    if ($category !== '') {
        // category is a slug
        $args['category_name'] = $category;
    }

    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('/parts/part', 'excerpt'); // adjust to your template
            $posts[] = [
                'id'      => get_the_ID(),
                'content' => ob_get_clean(),
            ];
        }
    }
    wp_reset_postdata();

    wp_send_json([
        'posts' => $posts,
        'total' => (int) $query->found_posts,
    ]);
}
add_action('wp_ajax_load_wp_posts', 'load_wp_posts_callback');
add_action('wp_ajax_nopriv_load_wp_posts', 'load_wp_posts_callback');


// ------------------------------------------
// AJAX callback: load all team members, with first_name + last_name + HTML
// ------------------------------------------
function load_team_members_callback() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $args = [
        'post_type'      => 'team',
        'posts_per_page' => -1,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
    ];

    if ($category) {
        $args['tax_query'] = [[
            'taxonomy' => 'team-category',
            'field'    => 'term_id',
            'terms'    => intval($category),
        ]];
    }

    $query = new WP_Query($args);
    $members = [];

    while ($query->have_posts()) {
        $query->the_post();

        $title = get_the_title();
        $first_name = strtok($title, ' ');
        $last_name  = trim(substr($title, strlen($first_name)));

        $team_type_terms = get_the_terms(get_the_ID(), 'team-type');
        $team_type = $team_type_terms && !is_wp_error($team_type_terms) ? $team_type_terms[0]->name : '';

        ob_start(); ?>
        <div class="team-member">
          <div class="profile">
            <div class="image">
              <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('team-member'); ?>
              <?php else: ?>
                <img src="<?= esc_url(get_template_directory_uri().'/assets/images/theme/profile.svg'); ?>"
                     alt="<?= esc_attr($title); ?>">
              <?php endif; ?>
            </div>
            <div class="title">
              <h3><?= esc_html($title); ?></h3>
              <p class="meta"><?= esc_html(get_field('position')); ?></p>
            </div>
          </div>
          <div class="bio">
            <div class="bio-content">
              <?php 
                $phone = get_field('contact_number');
                $linkedin = get_field('linkedin_profile');
                if ($phone || $linkedin):
              ?>
                <ul class="contact-details">
                  <?php if ($phone): 
                    $tel_link = preg_replace('/[^0-9+]/','',$phone); ?>
                    <li><a class="phone" href="tel:<?= esc_attr($tel_link) ?>"
                           rel="noopener noreferrer">Call <?= esc_html($phone) ?></a></li>
                  <?php endif; ?>
                  <?php if ($linkedin): ?>
                    <?php $fn = sanitize_text_field($first_name); ?>
                    <li><a class="li" href="<?= esc_url($linkedin) ?>"
                           rel="noopener noreferrer">View <?= esc_html($fn) ?> on LinkedIn</a></li>
                  <?php endif; ?>
                </ul>
              <?php endif; ?>
              <?php the_content(); ?>
            </div>
          </div>
        </div>
        <?php
        $html = ob_get_clean();

        $members[] = [
            'id'         => get_the_ID(),
            'title'      => $title,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'position'   => get_field('position'),
            'team_type'  => $team_type,
            'html'       => $html,
        ];
    }
    wp_reset_postdata();

    wp_send_json(['members' => $members]);
}
add_action('wp_ajax_load_team_members','load_team_members_callback');
add_action('wp_ajax_nopriv_load_team_members','load_team_members_callback');


