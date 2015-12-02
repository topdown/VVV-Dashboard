<?php

/*
 * -----------------------------------------
 * SETTINGS
 * -----------------------------------------
 */

define( 'VVV_DASH_DEBUG', false );
define('VVV_DASH_SCAN_PATHS', array('htdocs', 'public'));

// Cache settings
define( 'VVV_DASH_THEMES_TTL', 86400 );
define( 'VVV_DASH_PLUGINS_TTL', 86400 );
define( 'VVV_DASH_HOSTS_TTL', 86400 );
define('VVV_DASH_SCAN_DEPTH', 2);

/**
 * Redirects to the proper location
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    11/19/15, 1:12 PM
 *
 * @param     $url
 * @param int $status_code
 */
function redirect_to_vvv_dash( $url, $status_code = 301 ) {
	header( 'Location: ' . $url, true, $status_code );
	die();
}

if(! defined('VVV_DASH_BASE')) {
	redirect_to_vvv_dash( '/dashboard/index.php', 302 );
}
