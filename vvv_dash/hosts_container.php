<?php

/**
 *
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * PHP version 5
 *
 * LICENSE:
 *
 * Created 5/8/16, 10:37 AM
 *
 * @package    dashboard - hosts_container.php
 * @author     Jeff Behnke <code@validwebs.com>
 * @copyright  2009-2016 ValidWebs.com
 * @license
 * @version
 */

namespace vvv_dash;

/**
 * Class hosts_container
 */
class hosts_container {
	
	protected static $host_list = array();

	public function __construct() {

	}
	
	/**
	 * @return array $host_list
	 */
	public static function get_host_list() {
		return self::$host_list;
	}

	/**
	 * @param array $host_list
	 *
	 * @return array $host_list
	 */
	public static function set_host_list( $host_list ) {
		if(is_array( $host_list )) {
			return self::$host_list = array_merge( self::$host_list, $host_list );
		} else {
			return self::$host_list;
		}
	}

}

// End hosts_container.php