<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/2/15, 10:33 AM
 *
 * LICENSE: This is PRIVATE source code developed for clients.
 * It is in no way transferable and copy rights belong to Jeff Behnke @ ValidWebs.com
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * vvv-dashboard.php
 */

/**
 * Class vvv_dashboard
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 */
class vvv_dashboard {


	public function __construct() {

	}

	public function set_host_info( $host ) {

		$host_info = array();
		$hosts     = new vvv_dash_hosts();

		// Are these default hosts
		$type      = $hosts->check_host_type( $host );

		if ( isset( $type['key'] ) ) {

			if ( isset( $type['path'] ) ) {
				$host_info['host'] = $type['key'];
				$host_info['path'] = $type['path'];
				$host_info['content'] = '/wp-content';
			} else {
				$host_info['host'] = $type['key'];
				$host_info['path'] = '/';
				$host_info['content'] = '/wp-content';
			}

		} else {
			$host_info = $hosts->get_paths( $host );
		}

		$host_info['is_env'] = $hosts->is_env_site($host_info);

		return $host_info;
	}


	public function __destruct() {
		// TODO: Implement __destruct() method.
	}


}
// End vvv-dashboard.php