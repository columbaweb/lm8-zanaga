<?php
if (!defined('ABSPATH')) {
    exit;
}

// ------------------------------------------
// DUPLICATE POSTS
// ------------------------------------------

function lmn_duplicate_post_as_draft() {
    global $wpdb;

    // Check if post ID is provided and the action is correct
    if (!isset($_REQUEST['action']) || 'lmn_duplicate_post_as_draft' !== $_REQUEST['action'] || !isset($_GET['post']) && !isset($_POST['post'])) {
        wp_die('No post to duplicate has been supplied!');
    }

    // Nonce verification
    if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__))) {
        return;
    }

    // Get the original post ID
    $post_id = isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']);
    $post = get_post($post_id);

    // Set the new post author to current user
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;

    // Create the post duplicate if post data exists
    if ($post !== null) {
        // New post data array
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order,
        );

        // Insert the post
        $new_post_id = wp_insert_post($args);

        // Get all current post terms and set them to the new post draft
        $taxonomies = get_object_taxonomies($post->post_type);
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        // Duplicate all post meta
        $post_meta_infos = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%d", $post_id));
        if (!empty($post_meta_infos)) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES ";
            $values = array();
            foreach ($post_meta_infos as $meta_info) {
                if ($meta_info->meta_key === '_wp_old_slug') continue;
                $values[] = $wpdb->prepare("(%d, %s, %s)", $new_post_id, $meta_info->meta_key, $meta_info->meta_value);
            }
            $sql_query .= implode(", ", $values);
            $wpdb->query($sql_query);
        }

        // Redirect to the edit post screen for the new draft
        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    } else {
        wp_die('Post creation failed, could not find original post: ' . $post_id);
    }
}
add_action('admin_action_lmn_duplicate_post_as_draft', 'lmn_duplicate_post_as_draft');

// Add the duplicate link to action list for post_row_actions and page_row_actions
function lmn_duplicate_post_link($actions, $post) {
    if (current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=lmn_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
    }
    return $actions;
}
add_filter('post_row_actions', 'lmn_duplicate_post_link', 10, 2);
add_filter('page_row_actions', 'lmn_duplicate_post_link', 10, 2);
