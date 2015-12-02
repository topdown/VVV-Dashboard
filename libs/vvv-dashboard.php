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
class vvv_dashboard {


	public function __construct() {

	}

	public function set_host_info( $host ) {

		$host_info = array();
		$hosts     = new vvv_dash_hosts();
		$type      = $hosts->check_host_type( $host );

		if ( isset( $type['key'] ) ) {

			if ( isset( $type['path'] ) ) {
				$host_info['host'] = $type['key'];
				$host_info['path'] = $type['path'];
			} else {
				$host_info['host'] = $type['key'];
				$host_info['path'] = '/';
			}

		} else {
			$host              = strstr( $host, '.', true );
			$host_info['host'] = $host;
			$path         = VVV_WEB_ROOT . '/' . $host . '/htdocs';

			if ( is_dir( $path ) ) {
				$host_info['path'] = '/htdocs';
			} else {
				global $vvv_dash_scan_paths;

				foreach ( $vvv_dash_scan_paths as $key => $path ) {

					// Test alternate paths
					$path_test         = VVV_WEB_ROOT . '/' . $host . '/' . $path;
					if ( is_dir( $path_test ) ) {
						$host_info['path'] = $path_test;
					} else {
						$host_info['path'] = false;
					}
				} // end foreach

			}
		}

		return $host_info;
	}


	public function __destruct() {
		// TODO: Implement __destruct() method.
	}
}
// End vvv-dashboard.php