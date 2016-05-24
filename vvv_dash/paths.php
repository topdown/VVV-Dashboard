<?php

/**
 *
 * PHP version 5
 *
 * Created: 2/20/16, 12:09 AM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * paths.php
 */

namespace vvv_dash;

/**
 * Storage for the paths we need to work in.
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Class paths
 * @package        vvv_dash
 *
 */
class paths {

	/**
	 * @property array $_scan_paths
	 */
	private static $_scan_paths = array( 'default' => array( 'htdocs', 'public' ) );

	/**
	 * @property array $_wp_content_paths
	 */
	private static $_wp_content_paths = array( 'default' => array( 'wp-content', 'content' ) );

	/**
	 * Store our various scan paths
	 *
	 * <code>
	 * // Setting Custom public paths
	 * $scan_paths = array('custom' => array('foo', 'bar'));
	 * vvv_dash\paths::set_scan_paths($scan_paths);
	 * </code>
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/20/16, 12:28 AM
	 *
	 * @param array $paths
	 */
	public static function set_scan_paths( $paths = array() ) {
		self::$_scan_paths = array_merge( self::$_scan_paths, $paths );
	}

	/**
	 * Allow us to get the set paths
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/20/16, 12:29 AM
	 *
	 * @return array
	 */
	public static function get_scan_paths() {
		return self::$_scan_paths;
	}

	/**
	 * Store out wp content paths
	 *
	 * <code>
	 * // Custom content paths
	 * $wp_content_paths = array('custom' => array('foo', 'bar'));
	 * vvv_dash\paths::set_wp_content_paths($wp_content_paths);
	 * </code>
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/20/16, 12:30 AM
	 *
	 * @param array $paths
	 */
	public static function set_wp_content_paths( $paths = array() ) {

		self::$_wp_content_paths = array_merge( self::$_wp_content_paths, $paths );
	}

	/**
	 * Get the content paths
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/20/16, 12:30 AM
	 *
	 * @return array
	 */
	public static function get_wp_content_paths() {
		return self::$_wp_content_paths;
	}
}

// End paths.php