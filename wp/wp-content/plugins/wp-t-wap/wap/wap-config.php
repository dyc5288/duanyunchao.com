<?php
// set true for edit cookiepath
define('COOKIEPATH', '', true);
define('SITECOOKIEPATH', '', true);

/////////////* wp include start *///////////////
// set wordpress ( mu ) site's root
$wproot = "../";

require_once($wproot .'wp-config.php');
require_once($wproot .'wp-admin/includes/admin.php');
/////////////* wp include end */////////////////

require_once('wap-settings.php');

$wap_home = _get_wap_home();
$wp_home = get_option("home");

$cmp = strcmp ( $wap_home, $wp_home);

// wap is a VHOST
if ( $cmp > 0 )
{
    define('COOKIEPATH', preg_replace('|https?://[^/]+|i', '', get_option('home') . '/' ) );
	define('SITECOOKIEPATH', preg_replace('|https?://[^/]+|i', '', get_option('siteurl') . '/' ) );
}
else
{
    define('COOKIEPATH', preg_replace('|https?://[^/]+|i', '', $wap_home . '/' ) );
    define('SITECOOKIEPATH', preg_replace('|https?://[^/]+|i', '', $wap_home . '/' ) );
}

header('Content-Type: text/html; charset=' . get_bloginfo('charset'));
?>
