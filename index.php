<?php get_header(); ?>

<div class="wrap wide">
	<div id="content">
        <?php
            if (have_posts()) : while (have_posts()) : the_post();
                get_template_part( 'parts/part', 'excerpt' );
            endwhile;
                get_template_part('parts/part', 'pagination');  
            else :
                get_template_part( 'parts/part', 'none' );   
            endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>