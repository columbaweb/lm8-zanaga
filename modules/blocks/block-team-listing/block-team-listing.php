<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'team-listing' . 
    (!empty($block['className']) ? ' ' . $block['className'] : '') . 
    (!empty($block['align']) ? ' align' . $block['align'] : '');

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

<div class="<?= esc_attr($className); ?>">
    <div class="team-listing__wrap">
        <div class="team-item-wrapper">
            <?php if ($quote) { ?>
            <div class="team-head quote fade-in anim-0">
                <blockquote>
                    <?= $quote; ?>
                </blockquote>
            </div>
            <?php } ?>
        </div>

        <?php 
        if ($teamQuery->have_posts()):
            for ($i = 1; $teamQuery->have_posts(); $i++): $teamQuery->the_post();

            $has_content = get_the_content() ? true : false;
        ?>
        <div class="team-item-wrapper">
            <div class="team-head sh fade-in anim-<?= $i; ?>" <?= $has_content ? 'data-has-bio="true"' : ''; ?>>
                <div class="inner">
                    <div class="profile">
                        <?php 
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('team-member'); 
                        } else { 
                            echo '<img src="' . get_template_directory_uri() . '/assets/images/theme/profile.jpg" alt="' . esc_attr(get_the_title()) . '">';
                        }
                        ?>
                    </div>

                    <div class="team-details">
                        <div class="title">
                            <h3><?= get_the_title(); ?></h3>
                            <p class="meta"><?php the_field('position', $post); ?></p>
                            <span class="more">
                                <svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none"><circle cx="18" cy="18" r="17.5" stroke="#CD136A" transform="rotate(-90 18 18)"/><path fill="#0A2342" d="M18 9.724a.75.75 0 0 1 .75.75v6.776h6.776a.75.75 0 0 1 0 1.5H18.75v6.776a.75.75 0 0 1-1.5 0V18.75h-6.776a.75.75 0 0 1 0-1.5h6.776v-6.776a.75.75 0 0 1 .75-.75Z"/></svg>
                                Read more
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($has_content): ?>

            <div class="team-bio">
                <div class="inner">
                    <div class="col-profile">
                        <?php 
                            // if (has_post_thumbnail()) {
                            //     the_post_thumbnail('team-member'); 
                            // } else { 
                            //     echo '<img src="' . get_template_directory_uri() . '/assets/images/theme/profile.jpg" alt="' . esc_attr(get_the_title()) . '">';
                            // }
                        ?>
                        <h3><?= get_the_title(); ?></h3>
                        <p class="meta"><?php the_field('position', $post); ?></p>
                    </div>
                    <div class="col-bio">
                        <?= apply_filters('the_content', get_the_content()); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php 
            endfor;
        endif; 
        wp_reset_postdata();
        ?>
    </div>
</div>

