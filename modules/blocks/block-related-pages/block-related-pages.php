<?php
    // Set block ID and classes
    $id = !empty($block['anchor']) ? $block['anchor'] : 'lmn-' . $block['id'];
    $className = 'related-pages' 
        . (!empty($block['className']) ? ' ' . $block['className'] : '') 
        . (!empty($block['align']) ? ' align' . $block['align'] : '');
?>

<?php
$featured_posts = get_field('select_page');
if( $featured_posts ): 
?>
<ul class="<?= esc_attr($className); ?>">
    <li class="fade title">
        <h3>Discover more</h3>
    </li>
    <?php foreach( $featured_posts as $featured_post ): 
        $permalink = get_permalink( $featured_post->ID );
        $title = get_the_title( $featured_post->ID );
    ?>
    <li class="fade">
        <a href="<?php echo esc_url( $permalink ); ?>">
            <h4 class="equal"><?php echo esc_html( $title ); ?></h4>
            <span class="btn">
                <svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="21.48" height="12.474">
                    <g stroke="#000" stroke-width="2">
                        <path fill="none" d="m13.999.737 6 5.5-6 5.5"/>
                        <path fill="#fff" d="M19 6.236H0"/>
                    </g>
                </svg>
            </span>
        </a>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>