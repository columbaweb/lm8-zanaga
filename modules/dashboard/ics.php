<?php

// Add a custom endpoint "calendar"
function add_calendar_feed(){
	add_feed('icalevents', 'export_ics');

    // Only uncomment these 2 lines the first time you load this script, to update WP rewrite rules
    global $wp_rewrite;
    $wp_rewrite->flush_rules( false );
}
add_action('init', 'add_calendar_feed');

function export_ics() {
    // Query the event
    $the_event = new WP_Query(array(
        'p' => $_REQUEST['id'],
        'post_type' => 'calendar',
    ));

    if($the_event->have_posts()) :

        // Escape commas and semicolons for .ics
        function escapeString($string) {
            return preg_replace('/([\,;])/', '\\\$1', $string);
        }

        // Shorten string to max length
        function shorter_version($string, $length) {
            return (strlen($string) >= $length) ? substr($string, 0, $length) : $string;
        }

        while($the_event->have_posts()) : $the_event->the_post();

            $timestamp = date_i18n('Ymd\THis\Z', time(), true);
            $uid = get_the_ID();
            $created_date = get_post_time('Ymd\THis\Z', true, $uid);

            $start_date = get_field('event_date', $uid);
            $start_time = get_field('event_time', $uid);

            $end_date = get_field('event_end_date', $uid) ?: $start_date;
            $end_time = get_field('event_end_time', $uid) ?: $start_time;

            $isAllDayEvent = empty($start_time);

            if ($isAllDayEvent) {
                $dtstart_val = $start_date;
                $dtend_val = $end_date;
            } else {
                $dtstart_val = date('Ymd\THis\Z', strtotime($start_date . ' ' . $start_time));
                $dtend_val = date('Ymd\THis\Z', strtotime($end_date . ' ' . $end_time));
            }

            if (get_field('event_end_date', $uid)) {
                $end_date = get_field('event_end_date', $uid) + 1;
            } else {
                $end_date = $start_date;
            }

            $deadline = date_i18n("Ymd\THis\Z", get_post_meta($uid, 'event_date', true));

            $organiser = get_bloginfo('name');
            $address = ''; // Add location here if needed
            $summary = get_the_title();
            $content = trim(preg_replace('/\s\s+/', ' ', get_field('additional_content', $uid)));

            $filename = urlencode(get_the_title() . '-' . date('Y-m-d'));
            $eol = "\r\n";

            ob_start();

            header("Content-Description: File Transfer");
            header("Content-Disposition: inline; filename=" . $filename . ".ics");
            header('Content-type: text/calendar; charset=utf-8');
            header("Pragma: 0");
            header("Expires: 0");
?>

BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//<?php echo get_bloginfo('name') . $eol; ?>//NONSGML Events //EN
CALSCALE:GREGORIAN
X-WR-CALNAME:<?php echo get_bloginfo('name') . $eol; ?>
BEGIN:VEVENT
CREATED:<?php echo $start_date . $eol; ?>
UID:<?php echo $uid . $eol; ?>
<?php if ($isAllDayEvent): ?>
DTSTART;VALUE=DATE:<?php echo $dtstart_val . $eol; ?>
DTEND;VALUE=DATE:<?php echo $dtend_val . $eol; ?>
<?php else: ?>
DTSTART;VALUE=DATE-TIME:<?php echo $dtstart_val . $eol; ?>
DTEND;VALUE=DATE-TIME:<?php echo $dtend_val . $eol; ?>
<?php endif; ?>
DTSTAMP:<?php echo $timestamp . $eol; ?>
LOCATION:<?php echo escapeString($address) . $eol; ?>
SUMMARY:<?php echo escapeString(get_bloginfo('name')) . ' ' . escapeString(get_the_title()) . $eol; ?>
ORGANIZER:<?php echo escapeString($organiser) . $eol; ?>
DESCRIPTION:<?php echo get_bloginfo('name') . $eol; ?>\n\n\n<?php echo get_bloginfo('url') ?>/investors/financial-calendar/
TRANSP:OPAQUE
BEGIN:VALARM
ACTION:DISPLAY
TRIGGER;VALUE=DATE-TIME:<?php echo $deadline . $eol; ?>
DESCRIPTION:Reminder for <?php echo escapeString(get_the_title()) . $eol; ?>
END:VALARM
END:VEVENT

<?php
        endwhile;
?>
END:VCALENDAR
<?php
        $eventsical = ob_get_contents();
        ob_end_clean();
        echo $eventsical;
        exit();
    endif;
}
?>