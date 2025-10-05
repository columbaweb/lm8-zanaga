<?php

global $post;

// Retrieve ACF fields for the current post
$disable_child_link_menu = get_field('disable_child_link_menu', $post->ID);
$widgets_to_display = get_field('widgets_to_display', $post->ID);

// Determine the top-level parent
$parent = $post->post_parent ? end(get_post_ancestors($post->ID)) : $post->ID;

// Apply parent settings to child pages if specified
if ($post->post_parent && get_field('apply_to_child_pages', $parent)) {
    $disable_child_link_menu = $disable_child_link_menu ?: get_field('disable_child_link_menu', $parent);
    $widgets_to_display = $widgets_to_display ?: get_field('widgets_to_display', $parent);
}

?>

<aside id="sidebar">
    <div class="inner">
        
        <?php if (is_page()): ?>
            
            <?php if (!$disable_child_link_menu): ?>
                <div class="widget-area">
                    <?php
                    $children = wp_list_pages([
                        'title_li' => '',
                        'exclude' => '1105',
                        'child_of' => $parent,
                        'echo' => 0
                    ]);

                    if ($children) {
                        $parent_title = get_the_title($parent);
                        $parent_link = get_permalink($parent);
                        $same_page = ($parent === get_the_ID()) ? 'current' : '';

                        echo "<ul class='sidebar-menu'>";
                        echo "<li class='menu-item-has-children $same_page'><a href='$parent_link'>$parent_title</a></li>";
                        echo $children;
                        echo "</ul>";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($widgets_to_display && !in_array('none', $widgets_to_display)): ?>
                <?php foreach ($widgets_to_display as $widget): ?>
                    <div class="widget-area">
                        <?php dynamic_sidebar($widget); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        
        <?php elseif (is_singular('post')): ?>
            
            <div class="widget-area b2blog">
                <h5>Read more articles</h5>
                <a class="btn" href="<?= esc_url(home_url('/media/')); ?>">Go to media</a>
            </div>

            <div class="widget-area">
                <?php the_post_navigation([
                    'prev_text' => __('<span>Previous</span> <strong>%title</strong>', 'ir'),
                    'next_text' => __('<span>Next</span> <strong>%title</strong>', 'ir'),
                ]); ?>
            </div>

        <?php endif; ?>

        <?php //dynamic_sidebar('sidebar'); ?>
        
    </div>
</aside>