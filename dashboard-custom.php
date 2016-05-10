<?php

/*
 * -----------------------------------------
 * SETTINGS
 * -----------------------------------------
 */

define( 'VVV_DASH_DEBUG', false );

// I hate globals, will come back to this
// alternate paths
global $vvv_dash_scan_paths;
// Custom public paths
$vvv_dash_scan_paths = array('htdocs', 'public');

global $vvv_dash_wp_content_paths;
// Custom content paths
$vvv_dash_wp_content_paths = array('wp-content', 'content');

// New Theme Settings
define('VVV_DASH_NEW_THEME_AUTHOR', 'Jeff Behnke');
define('VVV_DASH_NEW_THEME_AUTHOR_URI', 'http://validwebs.com');
// --theme_uri

// New Plugin Settings
define('VVV_DASH_NEW_PLUGIN_AUTHOR', 'Jeff Behnke');
define('VVV_DASH_NEW_PLUGIN_AUTHOR_URI', 'http://validwebs.com');
// --plugin_uri
// --plugin_description

// Cache settings
define( 'VVV_DASH_THEMES_TTL', 86400 );
define( 'VVV_DASH_PLUGINS_TTL', 86400 );
define( 'VVV_DASH_HOSTS_TTL', 86400 );
// No longer used define('VVV_DASH_SCAN_DEPTH', 2);

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
