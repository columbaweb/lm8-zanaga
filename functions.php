<?php if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

# ------------------------------------------
# Gutenberg
# ------------------------------------------
require get_template_directory() . '/modules/blocks/blocks-functions.php';
require get_template_directory() . '/modules/blocks/blocks-register.php';

# ------------------------------------------
# Dashboard
# ------------------------------------------
require_once get_template_directory() . '/modules/dashboard/enqueue-styles.php';
require_once get_template_directory() . '/modules/dashboard/theme-setup.php';
require_once get_template_directory() . '/modules/dashboard/misc-functions.php';
require_once get_template_directory() . '/modules/dashboard/security.php';
require_once get_template_directory() . '/modules/dashboard/cpt.php';
require_once get_template_directory() . '/modules/dashboard/login.php';
require_once get_template_directory() . '/modules/dashboard/duplicate-posts.php';
require get_template_directory() . '/modules/dashboard/acf.php';
require get_template_directory() . '/modules/dashboard/enqueue-styles-core.php';
require get_template_directory() . '/modules/dashboard/ics.php';
require get_template_directory() . '/modules/dashboard/megamenu.php';

// optional
require get_template_directory() . '/modules/dashboard/ajax-posts.php';
//require get_template_directory() . '/modules/dashboard/facets.php';

