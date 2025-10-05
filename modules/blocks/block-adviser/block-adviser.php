<?php
// Set block ID and classes
$id = $block['anchor'] ?? 'block-' . $block['id'];
$className = 'company-grid' . 
    (!empty($block['className']) ? ' ' . $block['className'] : '') . 
    (!empty($block['align']) ? ' align' . $block['align'] : '');

$i = 0;
?>

<?php if (have_rows('advisers')): ?>
    <div class="<?= esc_attr($className); ?>">
        <?php while (have_rows('advisers')): the_row(); $i++;
            $title = esc_html(get_sub_field('title'));
            $name = get_sub_field('name');
            $image = get_sub_field('logo');
            $size = 'full';
            $link = get_sub_field('url');
            $link_url = $link['url'] ?? '';
            $link_title = $link['title'] ?? 'Visit website';
            $link_target = $link['target'] ?? '_self';
        ?>
        
        <<?= $link ? 'a' : 'div' ?> 
            class="company-grid__company fade-in anim-<?= esc_attr($i); ?>" 
            <?= $link ? 'href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '"' : '' ?>
        >
            <?php if ($title): ?>
                <h2 class="title equal"><?= $title; ?></h2>
            <?php endif; ?>

            <?php if ($image): ?>
                <?= wp_get_attachment_image($image, $size); ?>
            <?php endif; ?>
            
            <?= $name; ?>
        
        </<?= $link ? 'a' : 'div' ?>>
        <?php endwhile; ?> 
    </div>
<?php endif; ?>
