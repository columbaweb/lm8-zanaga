<?php

$id = $block['anchor'] ?? 'block-events-past-' . $block['id'];
$className = 'financial-calendar' . (!empty($block['className']) ? ' ' . $block['className'] : '');
$calendar = 'upcoming';
$className .= ' upcoming';

?>
<div class="<?php echo esc_attr($className); ?>">

    <?php
        $today = date('Ymd');
        $args = [
            'post_type' => 'calendar',
            'posts_per_page' => 1,
            'meta_key' => 'event_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                ],
            ],
        ];
        $eventQuery = new WP_Query($args);
        global $post;
    ?>

    <?php if ($eventQuery->have_posts()): ?>
        <?php while ($eventQuery->have_posts()): $eventQuery->the_post(); ?>
            <div class="event fade">
                <?php
                    $title = get_the_title();
                    $event_date = get_field('event_date', $post);
                    $event_end_date = get_field('event_end_date', $post);
                    $event_time = get_field('event_time', $post);
                    $event_end_time = get_field('event_end_time', $post);
                    $event_link = get_field('event_link', $post);
                    $event_location = get_field('event_location', $post);
                    $event_info = get_field('additional_information', $post);

                    $calDate = date_i18n('Ymd', strtotime($event_date));
                    $calDateFront = date_i18n('d M Y', strtotime($event_date));

                    if ($event_time) {
                        $calDateFront .= ' ' . date_i18n('g:i a', strtotime($event_time));
                    }

                    $calDateEnd = $event_end_date ? date_i18n('Ymd', strtotime($event_end_date)) : $calDate;
                    $calDateFrontEnd = $event_end_date ? date_i18n('d M Y', strtotime($event_end_date)) : '';

                    if ($event_end_time) {
                        $calDateFrontEnd .= ' ' . date_i18n('g:i a', strtotime($event_end_time));
                    }

                    $isFullDayEvent = empty($event_time);
                    $googleCalStartDate = $isFullDayEvent ? $calDate : $calDate . 'T' . date('His', strtotime($event_time)) . 'Z';
                    $googleCalEndDate = $isFullDayEvent ? $calDate : $calDateEnd . 'T' . date('His', strtotime($event_end_time ?: $event_time)) . 'Z';
                ?>

                <div class="event__head">
                    <p class="event__title">
                        <span class="meta"><em><?= $calDateFront; ?></em></span>
                        <span class="title">
                            <?php if ($event_link): ?>
                                <a href="<?= esc_url($event_link); ?>" target="_blank"><?= $title; ?></a>
                            <?php else: ?>
                                <?= $title; ?>
                            <?php endif; ?>
                        </span>
                        <?php if ($event_location): ?>
                            <span class="location">Location: <?= $event_location; ?></span>
                        <?php endif; ?>
                    </p>

                    <div class="event__actions">
                        <button class="btn-add-to-calendar" type="button">
                            <?= file_get_contents(get_stylesheet_directory() . '/assets/images/theme/icon-plus.svg'); ?>
                            Add to calendar
                         </button>
                        <ul class="calendar-links">
                            <li>
                                <a class="cal-link" href="<?= get_feed_link('icalevents'); ?>?id=<?= get_the_ID(); ?>">iCal</a>
                            </li>
                            <li>
                                <a class="cal-link" href="<?= get_feed_link('icalevents'); ?>?id=<?= get_the_ID(); ?>">Outlook</a>
                            </li>
                            <li>
                                <a class="cal-link" target="_blank" href="https://www.google.com/calendar/event?action=TEMPLATE&text=<?= urlencode(get_bloginfo('name') . ' - ' . $title); ?>&dates=<?= $googleCalStartDate; ?>/<?= $googleCalEndDate; ?>&details=<?= urlencode($title); ?>&location=<?= urlencode($event_location); ?>">Google</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No upcoming events are currently scheduled</p>
    <?php endif; wp_reset_postdata(); ?>

</div>