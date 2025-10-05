<?php

$page_id = get_the_ID();
$banner_content = get_field('video_banner', $page_id);
$banner_title = get_field('titles_in_banner', 'option') ?? null;

// Determine page title
$page_title = is_404() ? 'Page Not Found' :
    (is_search() ? 'Search Results for: ' . get_search_query() :
    (is_archive() ? get_the_archive_title() : get_the_title($page_id)));

// Determine banner image
if (is_404()) {
    $image_data = get_field('fourofour_banner_styles', 'option');
} elseif (is_search()) {
    $image_data = get_field('search_banner_styles', 'option');
} elseif (isset($banner_content['image'])) {
    $image_data = $banner_content;
} else {
    $image_data = get_field('default_banner_styles', 'option');
}

$image_id = $image_data['image'] ?? null;
if (!$image_id) {
    $default_image_data = get_field('default_banner_styles', 'option');
    $image_id = $default_image_data['image'] ?? null;
}

$banner_type = $banner_content['video_type'] ?? '';

if ($banner_type === 'external') {
    $video_url = $banner_content['video_link'] ?? '';
    $video_info = get_video_info($video_url);
    $video_type = $video_info['video_type'];
    $video_src = $video_info['video_src'];
}

if ($banner_type === 'hosted') {
    $video_url = $banner_content['video_file'] ?? '';
}

$title = $banner_content['title'] ?? '';
$subtitle = $banner_content['subtitle'] ?? '';
$tool = $banner_content['tool'] ?? '';
?>

<div class="inner">
    <svg width="100%" height="0" viewBox="0 0 1077 731" class="hero-mask" preserveAspectRatio="xMidYMid slice">
        <defs>
            <clipPath id="heroClip" clipPathUnits="objectBoundingBox">
                <path d="M0.409,0.0096 L0.0056,0.674 C0.0025,0.68 0.0001,0.695 0.0001,0.695 L0.0001,0.972 C0.0001,0.987 0.0083,1 0.0186,1 L0.668,1 C0.679,1 0.6875,0.988 0.6875,0.971 L0.6875,0.827 C0.6875,0.82 0.6925,0.809 0.6925,0.809 L0.766,0.692 C0.770,0.687 0.775,0.683 0.78,0.683 L0.98,0.683 C0.99,0.683 1,0.673 1,0.656 L1,0.028 C1,0.013 0.991,0.001 0.98,0.001 L0.423,0.001 C0.415,0.001 0.412,0.004 0.409,0.0096 Z"/>
            </clipPath>
        </defs>
    </svg>

<?php if ($image_id): ?>
    <div class="hero-video__feat">
        
        <div class="hero-video__image">

            <svg class="hero-bg" role="img" aria-hidden="true" viewBox="0 0 1097 751" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M443.31 7.08 7.64 494.146a29.999 29.999 0 0 0-7.64 20v212.268c0 13.255 10.745 24 24 24h713.711c13.255 0 24-10.745 24-24V621.115c0-4.982 1.86-9.785 5.214-13.468l74.807-82.127a19.998 19.998 0 0 1 14.786-6.532H1073c13.25 0 24-10.745 24-24V24.414c0-13.255-10.75-24-24-24H458.217A20 20 0 0 0 443.31 7.08Z" fill="#ddddd7"/>
            </svg>
            <!--
            <svg class="hero-bg" role="img" aria-hidden="true" viewBox="0 0 1097 751" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M443.31 7.08 7.64 494.146a29.999 29.999 0 0 0-7.64 20v212.268c0 13.255 10.745 24 24 24h713.711c13.255 0 24-10.745 24-24V621.115c0-4.982 1.86-9.785 5.214-13.468l74.807-82.127a19.998 19.998 0 0 1 14.786-6.532H1073c13.25 0 24-10.745 24-24V24.414c0-13.255-10.75-24-24-24H458.217A20 20 0 0 0 443.31 7.08Z" fill="url(#a)"/>
                <defs>
                    <linearGradient id="a" x1="1088.42" y1="-11.972" x2="26.02" y2="754.94" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#EBEBE8"/>
                        <stop offset="1" stop-color="#CBC9C7"/>
                    </linearGradient>
                </defs>
            </svg>
-->

            <div class="video-clip">
                <?php if ($banner_type == 'external'): ?>
                    
                    <?= wp_get_attachment_image($image_id, 'full', false, ['class' => 'banner-image']); ?>
                    <iframe title="banner" class="video-player" data-type="<?php echo $video_type; ?>" src="<?php echo $video_src; ?>" allowfullscreen></iframe>

                <?php elseif ($banner_type == 'hosted') : ?>
                    <?= wp_get_attachment_image($image_id, 'full', false, ['class' => 'banner-image']); ?>

                    <video class="video-player" data-type="hosted" autoplay muted loop playsinline aria-hidden="true">
                        <source src="<?= $video_url; ?>" type="video/mp4">
                    </video>

                <?php endif; ?>
            </div>
        </div>

        <?php if ($tool): ?>
            <div class="hero-video__iframe has-global-padding">
                <iframe src="<?= $tool; ?>" title="<?php bloginfo('name'); ?> share price tool"></iframe>
            </div>
        <?php endif; ?>

    </div>
<?php endif; ?>

<?php if ($banner_title && !is_front_page()) : ?>
    <div class="hero-video__content">
        <div class="has-global-padding">
            <h1 class="page-title"><?= esc_html($page_title); ?></h1>
            <?php if ($subtitle) : ?>
                <p class="hero-image__subtitle"><?= esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (is_front_page()) : ?>
    <div class="hero-video__content front">
        <div class="has-global-padding">
            <h2 class="hero-image__title"><?= $title; ?></h2>
            <?php if ($subtitle) : ?>
                <p class="hero-image__subtitle"><?= $subtitle; ?></p>
            <?php endif; ?>

            <svg id="scrolldown" aria-hidden="true" role="img" width="44" height="45" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="22" cy="22.104" r="21.173" stroke="#CD136A" stroke-width="1.5"/><path d="M21.951 10.844a.75.75 0 0 1 .75.75v17.99l9.014-9.11a.75.75 0 0 1 1.066 1.055L22.488 31.934a.751.751 0 0 1-1.068-.002L11.218 21.527l-.052-.058a.75.75 0 0 1 1.066-1.045l.056.052 8.913 9.09V11.594a.75.75 0 0 1 .75-.75Z" fill="#0A2342"/></svg>
        </div>
    </div>
<?php endif; ?>
</div>