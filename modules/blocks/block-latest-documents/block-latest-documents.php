<?php
$id = $block['anchor'] ?? 'latest-documents-' . $block['id'];
$className = 'latest-documents' . 
    (!empty($block['className']) ? ' ' . $block['className'] : '') . 
    (!empty($block['align']) ? ' align' . $block['align'] : '');

$cat = get_field('select_categories');
$ppp = get_field('number_of_files') ?? 3;

$args = [
    'post_type' => 'documents',
    'posts_per_page' => $ppp,
    'order' => 'DESC',
    'tax_query' => [
        [
            'taxonomy' => 'document-type',
            'field' => 'term_id',
            'terms' => $cat
        ],
    ],
];

$fileQuery = new WP_Query($args);
?>

<ul id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">
    <?php if ($fileQuery->have_posts()): ?>
        <?php while ($fileQuery->have_posts()): $fileQuery->the_post(); ?>
            <li>
                <a class="file" href="<?php the_permalink(); ?>" target="_blank">
                    <?= file_get_contents(get_stylesheet_directory() . '/assets/images/theme/icon-pdf.svg'); ?>
                    <span class="meta"><?= esc_html(get_the_date('j M Y')); ?></span>
                    <h3><?= esc_html(get_the_title()); ?></h3>
                </a>
            </li>
        <?php endwhile; ?>
    <?php endif; wp_reset_postdata(); ?>
</ul>