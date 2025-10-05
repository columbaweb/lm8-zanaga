<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'team-listing' . 
    (!empty($block['className']) ? ' ' . $block['className'] : '') . 
    (!empty($block['align']) ? ' align' . $block['align'] : '');

// Get category name from ACF field
$team_category = get_field('team_category');
$category_name = get_term_by('id', $team_category, 'team-category')->name;
$quote = get_field('quote');

$args = [
    'post_type'      => 'team',
    'posts_per_page' => -1,
    'order'          => 'ASC',
    'orderby'        => 'menu_order',
    'tax_query'      => [
        [
            'taxonomy' => 'team-category',
            'field'    => 'id',
            'terms'    => $team_category,
        ],
    ],
];
$teamQuery = new WP_Query($args);
global $post;
?>

<div class="team-listing-wrap">
    <div class="<?= esc_attr($className); ?>">

        <?php if ($quote) { ?>
        <div class="team-head quote fade-in anim-0">
            <blockquote>
                <?= $quote; ?>
            </blockquote>
        </div>
        <div class="team-bio">
            <div class="inner">
                test
            </div>
        </div>
        <?php } ?>
        <?php 
            if ($teamQuery->have_posts()): 
            for ($i=1; $teamQuery->have_posts(); $i++): $teamQuery->the_post(); 
        ?>
            <div class="team-head fade-in anim-<?= $i; ?>">
                <div class="profile">
                    
                    <?php 
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('team-member'); 
                        } else { 
                            echo '<img src="' . get_template_directory_uri() . '/assets/images/theme/profile.jpg" alt="' . esc_attr(get_the_title()) . '">';
                        } 
                    ?>

                    <?php
                        $selected_committees = get_field('committee', $post);

                        if ($selected_committees) {
                            echo '<ul class="committees">';
                            foreach ($selected_committees as $committee) {
                                $value = $committee;
                                $first_letter = strtoupper($value[0]);
                                echo '<li class="'.$value.'">' . htmlspecialchars($first_letter) . '</li>';
                            }
                            echo '</ul>';
                        }
                    ?>
                </div>

                <div class="team-details sh">
                    <div class="title">
                        <h3><?= get_the_title(); ?></h3>
                        <p class="meta"><?php the_field('position', $post); ?></p>
                    </div>
                </div>
            </div>

            <?php if (!is_admin()): ?>
            <div class="team-bio">
                <div class="inner">
                    <?php the_content(); ?>
                </div>
            </div>
            <?php endif; ?>

        <?php endfor; endif; wp_reset_query(); ?>
    </div>
</div>