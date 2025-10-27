<?php
// Set block ID and classes
$id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
$className = 'icon-list' 
    . (!empty($block['className']) ? ' ' . $block['className'] : '') 
    . (!empty($block['align']) ? ' align' . $block['align'] : '');
?>

<?php if( have_rows('icon_list') ): ?>
<ul class="<?= $className; ?> fade-in">
    <?php while (have_rows('icon_list')): the_row(); 
        $title = get_sub_field('title');
        $subtitle = get_sub_field('subtitle');
        $icon = get_sub_field('icon');
    ?>
        <li class="icon-list-item">
            <?= wp_get_attachment_image($icon, 'full'); ?>
            
            <span class="list-content">
                <?php if ($title) {
                    echo '<h3>'.$title.'</h3>';  
                } ?>
                
                <?php if ($subtitle) {
                    echo $subtitle;  
                } ?>
            </span>
        </li>
    <?php endwhile; ?>
</ul>
<?php endif; ?>



