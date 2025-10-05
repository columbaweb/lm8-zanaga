<?php if( have_rows('tabs_navigation') ): ?>
    <ul class="tabs<?php if (get_field('fixed_width_buttons')) { echo " fw"; } ?>">
    <?php while( have_rows('tabs_navigation') ): the_row(); ?>
        <li class="tab-label">
            <a href="#<?php the_sub_field('id'); ?>">
                <?php the_sub_field('title'); ?>
            </a> 
        </li>
		<?php the_sub_field('subfield_name'); ?>
    <?php endwhile; ?> 
    </ul>
<?php endif; ?>

