<?php

$id = (!empty($block['anchor'])) ? $block['anchor'] : 'block-' . $block['id'];
$className = 'file-table acc-panel';
$className .= (!empty($block['className'])) ? ' ' . $block['className'] : '';
$className .= (!empty($block['align'])) ? ' align' . $block['align'] : '';

$category = get_field('category');
$date = get_field('show_date');
$icon = get_field('show_icon');
$format = get_field('show_format');
$size = get_field('show_size');
$display_by_year = get_field('display_by_year');
$display_years = get_field('display_years');
$tab_style = get_field('fw_tabs');

$args = [
    'post_type'      => 'documents',
    'posts_per_page' => -1,
    'order'          => 'DESC',
    'tax_query'      => [
        [
            'taxonomy' => 'document-type',
            'field'    => 'id',
            'terms'    => $category,
        ]
    ]
];

if (!function_exists('unique_populate_post_data')) {
    function unique_populate_post_data($post_id) {
        $file = get_field('file', $post_id);
        $attachment_id = attachment_url_to_postid($file);
        $filesize = filesize(get_attached_file($attachment_id));
        $filesize = size_format($filesize);
    
        // Get document-type terms now
        $terms = get_the_terms($post_id, 'document-type');
        $term_slugs = [];
    
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $term_slugs[] = $term->slug;
            }
        }
    
        return [
            'id' => $post_id,
            'permalink' => get_permalink($post_id),
            'title' => get_the_title($post_id),
            'date' => get_the_time('j M Y', $post_id),
            'format' => pathinfo($file, PATHINFO_EXTENSION),
            'size' => $filesize,
            'terms' => $term_slugs, // new: save terms
        ];
    }
}

if (!function_exists('unique_display_document')) {
    function unique_display_document($post, $show_date, $show_icon, $show_format, $show_size) {
        $docdate = new DateTime($post['date']);
        $fileSize = $post['size'];
        $permalink = $post['permalink'];

        echo '<a class="file fade-in" href="'.$permalink.'" target="_blank" aria-label="'.$post['title'].' PDF" title="'.$post['title'].' PDF">';
        if ($show_date) {
            echo '<span class="date">' . $docdate->format('j M Y') . '</span>';
        }
        echo '<span class="title"><span>' . $post['title'] . '</span></span>';

        if ($show_icon) {
            echo '<span class="icon">';
            $icon = 'icon-announcement.svg'; // default
            if (!empty($post['terms']) && in_array('presentations', $post['terms'])) {
                $icon = 'icon-presentation.svg';
            }
            echo file_get_contents(get_template_directory() . '/assets/images/theme/' . $icon);
            echo '</span>';
        }

        if ($show_format) {
            echo '<span class="format">'. $post['format'] .'</span>';
        }
        if ($show_size) {
            echo '<span class="size">'. $fileSize .'</span>';
        }
        echo '</a>';
    }
}

$docsQuery = new WP_Query($args);
$posts_data = [];

if ($docsQuery->have_posts()) {
    while ($docsQuery->have_posts()) {
        $docsQuery->the_post();
        $post_id = get_the_ID();
        $year = get_the_time('Y', $post_id);

        $posts_data[$year][] = unique_populate_post_data($post_id);
    }
}
wp_reset_postdata();
krsort($posts_data);

$years = array_keys($posts_data);
$yearbreak = $display_years;
//$archive_has_content = false; 

// Check if there are posts in the archive section
$archive_has_content = false;
foreach ($years as $year) {
    if (!in_array($year, array_slice($years, 0, $yearbreak))) {
        $archive_has_content = true;
        break;
    }
}

if ($display_by_year) {
    ?>
    <div class="tab-group acc first-active file-tabs">
    <ul class="tabs<?php if ($tab_style) {echo " fw-tabs";} ?>">
    <?php
    for ($i = 0; $i < count($years); $i++) {
        if ($i < $yearbreak) {
            //echo '<li class="tab-label"><a class="tabs-button" href="#y-' . $years[$i] . '">' . $years[$i] . '</a></li>';
            echo '<li class="tab-label"><a class="tabs-button" href="#' . esc_attr($id) . '-y-' . $years[$i] . '">' . $years[$i] . '</a></li>';
        }
    }

    if ($archive_has_content) {
        //echo '<li class="tab-label archive"><a class="tabs-button" href="#archive">Archive</a></li>';
        echo '<li class="tab-label archive"><a class="tabs-button" href="#' . esc_attr($id) . '-archive">Archive</a></li>';
    }
    ?>
    </ul>

    <?php
    $first_year_div = true;
    for ($i = 0; $i < count($years); $i++) {
        if ($i < $yearbreak) {
            $uniqieID = $years[$i] . '-' . uniqid();
            //echo '<div id="y-' . $years[$i] . '" class="tab-panel">';
            echo '<div id="' . esc_attr($id) . '-y-' . $years[$i] . '" class="tab-panel">';
            echo '<input id="p-' . $uniqieID . '" type="checkbox"/>';
            echo '<label for="p-' . $uniqieID . '" class="acc-title"><span>' . $years[$i] . '</span></label>';
            echo '<div class="' . esc_attr($className) . '">';
            foreach ($posts_data[$years[$i]] as $post) {
                unique_display_document($post, $date, $icon, $format, $size);
            }
            echo '</div></div>';
        } else {
            $archive_has_content = true; 
            if ($first_year_div) {
                $uniqieAID = 'archive-' . uniqid();
                //echo '<div id="archive" class="tab-panel">';
                echo '<div id="' . esc_attr($id) . '-archive" class="tab-panel">';
                echo '<input id="p-archive-' . $uniqieAID . '" type="checkbox"/>';
                echo '<label for="p-archive-' . $uniqieAID . '" class="acc-title"><span>Archive</span></label>';
                echo '<div class="' . esc_attr($className) . '">';
                $first_year_div = false;
            }
            echo '<div class="archived-year">';
            echo '<h4>' . $years[$i] . '</h4>';
            foreach ($posts_data[$years[$i]] as $post) {
                unique_display_document($post, $date, $icon, $format, $size);
            }
            echo '</div>';
        }
    }

    if (!$first_year_div) {
        echo '</div></div>';
    }
    echo '</div>';
} else {
    echo '<div class="file-table">';
    foreach ($posts_data as $year => $posts) {
        foreach ($posts as $post) {
            unique_display_document($post, $date, $icon, $format, $size);
        }
    }
    echo '</div>';
}
?>