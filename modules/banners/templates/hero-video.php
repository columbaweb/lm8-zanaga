<?php

$page_id        = get_the_ID();
$banner_content = get_field('video_banner', $page_id) ?: [];
$banner_title   = get_field('titles_in_banner', 'option') ?? null;

/*
|--------------------------------------------------------------------------
| PAGE TITLE (match static banner logic)
|--------------------------------------------------------------------------
*/
$page_title = match (true) {
    is_404() => 'Page Not Found',
    is_search() => '<span class="span-1">Search results for</span> <span class="span-2 pink">' . get_search_query() . '</span>',
    is_archive() => get_the_archive_title(),
    is_singular('team') => 'Team',
    is_singular('post') => 'News & Insights',
    default => get_the_title($page_id),
};

/*
|--------------------------------------------------------------------------
| IMAGE FALLBACK (same as static)
|--------------------------------------------------------------------------
*/
$image_data = match (true) {
    is_404() => get_field('fourofour_banner_styles', 'option'),
    is_search() => get_field('search_banner_styles', 'option'),
    is_singular('post') => has_post_thumbnail()
        ? ['image' => get_post_thumbnail_id($page_id)]
        : get_field('search_banner_styles', 'option'),
    is_singular('team') => get_field('default_banner_styles', 'option'),
    !empty($banner_content['image']) => $banner_content,
    default => get_field('default_banner_styles', 'option'),
};

$image_id = $image_data['image'] ?? (get_field('default_banner_styles', 'option')['image'] ?? null);

/*
|--------------------------------------------------------------------------
| VIDEO SETUP
|--------------------------------------------------------------------------
*/
$banner_type = $banner_content['video_type'] ?? '';

$video_url  = '';
$video_type = '';
$video_src  = '';

if ($banner_type === 'external' && !empty($banner_content['video_link'])) {
    $video_info = get_video_info($banner_content['video_link']);
    $video_type = $video_info['video_type'] ?? '';
    $video_src  = $video_info['video_src'] ?? '';
}

if ($banner_type === 'hosted' && !empty($banner_content['video_file'])) {
    $video_url = $banner_content['video_file'];
}

/*
|--------------------------------------------------------------------------
| CONTENT FIELDS
|--------------------------------------------------------------------------
*/
$title    = $banner_content['title'] ?? '';
$subtitle = $banner_content['subtitle'] ?? '';
$tool     = $banner_content['iframe'] ?? '';

?>
<div class="hero-video">

    <!-- FULL WIDTH FEATURE IMAGE / VIDEO -->
    <?php if ($image_id): ?>
    <div class="hero-video__feat">

        <div class="hero-video__image">

            <!-- Fallback poster image -->
            <?= wp_get_attachment_image($image_id, 'full', false, ['class' => 'banner-image']); ?>

            <div class="video-clip">
                <?php if ($banner_type === 'external' && $video_src): ?>
                    <iframe
                        class="video-player"
                        title="Video Banner"
                        data-type="<?= esc_attr($video_type); ?>"
                        src="<?= esc_url($video_src); ?>"
                        allowfullscreen
                    ></iframe>

                <?php elseif ($banner_type === 'hosted' && $video_url): ?>
                    <video
                        class="video-player"
                        autoplay
                        muted
                        loop
                        playsinline
                        aria-hidden="true"
                        data-type="hosted"
                    >
                        <source src="<?= esc_url($video_url); ?>" type="video/mp4">
                    </video>

                <?php endif; ?>
            </div>

        </div>

    </div>
    <?php endif; ?>


    <!-- INNER PAGE TITLE + TEXT -->
    <?php if ($banner_title && !is_front_page()): ?>
    <div class="hero-video__content">
        <div class="wrap">

            <?php if ($title): ?>
                <h1 class="hero-image__title"><?= wp_kses_post($title); ?></h1>
            <?php else: ?>
                <h1 class="hero-image__title"><?= wp_kses_post($page_title); ?></h1>
            <?php endif; ?>

            <?php if ($subtitle): ?>
                <p class="hero-image__subtitle"><?= wp_kses_post($subtitle); ?></p>
            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>

    <?php if (is_front_page()): ?>
    <div class="hero-video__content front">
        <div class="has-global-padding">
            <div class="wrap">

                <div class="content-inner zoom-out">
                    <?php if ($title): ?>
                        <h2 class="hero-image__title"><?= wp_kses_post($title); ?></h2>
                    <?php else: ?>
                        <h2 class="hero-image__title"><?= wp_kses_post($page_title); ?></h2>
                    <?php endif; ?>

                    <?php if ($subtitle): ?>
                        <p class="hero-image__subtitle"><?= wp_kses_post($subtitle); ?></p>
                    <?php endif; ?>
                </div>

                <?php if ($tool): ?>
                <div class="hero-video__iframe">
                    <div class="content-iframe">
                        <div class="lmn-iframe">
                            <div class="inner">
                                <iframe src="<?= esc_url($tool); ?>" title="<?php bloginfo('name'); ?> share price tool"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <svg id="scrolldown" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 62 62" fill="none">
                    <path class="outline" stroke="#FF6139" d="M60.667 31v.378C60.462 47.59 47.257 60.672 31 60.672c-11.73 0-21.874-6.81-26.687-16.697h-.001a28.606 28.606 0 0 1-1.025-2.372 29.276 29.276 0 0 1-1.958-10.604c0-3.816.732-7.458 2.043-10.812v.001a29.706 29.706 0 0 1 6.655-10.165v-.001C15.394 4.65 22.806 1.328 31 1.328c.713 0 1.42.034 2.127.085h.001c.8.055 1.592.135 2.37.254h.003c.796.118 1.582.283 2.37.468a29.668 29.668 0 0 1 15.257 9.113l.38.434A29.523 29.523 0 0 1 60.668 31Z"/>
                    <path fill="#FF6139" d="M55.996 31c0-6.218-2.271-11.902-6.032-16.276A25.004 25.004 0 0 0 36.788 6.68a24.356 24.356 0 0 0-1.998-.395c-.659-.1-1.326-.167-1.998-.214A25.273 25.273 0 0 0 31 6c-6.905 0-13.15 2.8-17.671 7.326A24.97 24.97 0 0 0 6 31c0 3.216.588 6.159 1.65 8.934.26.68.545 1.347.864 1.998C12.57 50.262 21.115 56 31 56c13.805 0 25-11.197 25-25.004l-.004.004Z"/>
                    <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M25.824 36.912 30.854 42 36 36.912"/>
                    <path stroke="#fff" stroke-linecap="round" stroke-width="2" d="M30.912 41V21"/>
                </svg>

            </div>
        </div>
    </div>
    <?php endif; ?>

</div>