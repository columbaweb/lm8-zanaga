<?php 
    $external_link = get_post_meta(get_the_ID(), 'external_link', true);
    $permalink = get_permalink();
    $is_vimeo = strpos($external_link, 'vimeo.com') !== false;
    $is_youtube = strpos($external_link, 'youtube.com') !== false || strpos($external_link, 'youtu.be') !== false;
    $is_lightbox_video = $is_vimeo || $is_youtube;

    $url = !empty($external_link) ? esc_url($external_link) : esc_url($permalink);
    $target = (!empty($external_link) && !$is_lightbox_video) ? "_blank" : "_self";

    $thumbnail_url = get_the_post_thumbnail_url() ?: get_template_directory_uri() . '/assets/images/theme/news.jpg';

    $categories = get_the_category();
    $category_class = !empty($categories) ? 'category-' . sanitize_title($categories[0]->cat_name) : '';
    $link_class = 'excerpt ' . esc_attr($category_class);

?>

<a href="<?= $url ?>" class="<?= esc_attr($link_class) ?><?= $is_lightbox_video ? ' glightbox' : '' ?>" <?= $is_lightbox_video ? 'data-type="video" data-title="' . esc_attr(get_the_title()) . '"' : 'target="' . esc_attr($target) . '" title="' . esc_attr(get_the_title()) . '"' ?>>

    <div class="excerpt__thumb">
        <img src="<?= esc_url($thumbnail_url) ?>" alt="<?= esc_attr(get_the_title()) ?>">
    </div>

    <div class="excerpt__body">
        <div class="meta">
            <span class="date"> <?= esc_html(get_the_time('j F Y')); ?> </span>
        </div>

        <h3 class="excerpt__title"><?php the_title(); ?></h3>

        <span class="btn">
        <?php if ($is_lightbox_video) {
            echo file_get_contents(get_template_directory() . '/assets/images/theme/icon-play.svg');
        } else {
            echo file_get_contents(get_template_directory() . '/assets/images/theme/icon-ext.svg');
        } ?>
        </span>
    </div>
</a>