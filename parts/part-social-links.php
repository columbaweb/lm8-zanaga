<?php 
	$icon_type = get_field('icon_type', 'option');
	if ( have_rows('social_media', 'option') ) : 
?>
<ul class="social-links">    
    <?php 
    while ( have_rows('social_media', 'option') ) : the_row(); 
        $profile_url = get_sub_field('social_media_url');
        $parsed_url = parse_url($profile_url);

        $host = 'Unknown';
		// Keep only name of the host
        if (isset($parsed_url['host'])) {
            $host = preg_replace('/www\.|\.[^.]+$/', '', $parsed_url['host']);
        }

        // Path to the social media icon based on the URL and icon type
        $icon_path = get_template_directory() . '/assets/images/brands/' . ($icon_type == 'square' ? 'square-' : '') . $host . '.svg';
        $social_media_icon = file_get_contents($icon_path);
    ?>
        <li>
            <a class="social-links__link" href="<?= esc_url($profile_url); ?>" target="_blank" aria-label="Visit <?= $host; ?>">
                <?= $social_media_icon; ?>
                <span class="screen-reader-text">Visit <?= $host; ?> profile</span>
            </a>
        </li>
    <?php endwhile; ?> 
</ul>    
<?php endif; ?>
