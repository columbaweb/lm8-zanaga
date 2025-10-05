<?php
$id = $block['anchor'] ?? ('block-' . $block['id']);
$className = 'latest-posts' .
    (!empty($block['className']) ? ' ' . $block['className'] : '') .
    (!empty($block['align']) ? ' align' . $block['align'] : '');

?>
<div class="<?= esc_attr($className) ?>">
    <?php
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query->have_posts()) : 
            $query->the_post();
            get_template_part('parts/part', 'excerpt');
        endwhile;
        wp_reset_postdata();
    endif;
    ?>
</div>