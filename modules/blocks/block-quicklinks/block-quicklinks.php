<?php
    // Set block ID and classes
    $id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
    $className = 'link-group' 
        . (!empty($block['className']) ? ' ' . $block['className'] : '') 
        . (!empty($block['align']) ? ' align' . $block['align'] : '');

    // Get ACF fields
    $title = get_field('title');
?>

<div class="<?= esc_attr($className); ?>">
    <?php if ($title) { ?>
        <h2><?= $title; ?></h2>
    <?php } ?> 

<?php if( have_rows('links') ): ?>
    <ul class="link-group__list">
    <?php 
        while( have_rows('links') ): the_row(); 
        $link = get_sub_field('link');
        $link_url = $link['url'] ?? '';
        $link_target = !empty($link['target']) && $link['target'] === '_blank' ? '_blank' : '_self';
    ?>
        <li class="link-group__item">
            <a href="<?= esc_url($link_url) ?>" target="<?= esc_attr($link_target) ?>">
                <span><?= esc_html($link['title'] ?? 'Read More') ?></span>
                <span class="btn">
                    <svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="23.073" height="12.15"><g stroke="#000" stroke-width="1.5"><path fill="none" d="m15.332.575 6.571 5.5-6.571 5.5"/><path fill="#fff" d="M20.81 6.074H0"/></g></svg>
                </span>
            </a>
        </li>
    <?php endwhile; ?>
    </ul>
<?php endif; ?>

</div>