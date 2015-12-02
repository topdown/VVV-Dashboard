<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/2/15, 10:45 AM
 *
 * LICENSE: This is PRIVATE source code developed for clients.
 * It is in no way transferable and copy rights belong to Jeff Behnke @ ValidWebs.com
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * vvv-dash-hosts.php
 */
class vvv_dash_hosts {

	/**
	 * Checks to see if its a default host
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/2/15, 10:44 AM
	 *
	 * @param $host
	 *
	 * @return array
	 */
	public function check_host_type( $host ) {

		switch ( trim( $host ) ) {
			case 'local.wordpress.dev' :
				$host = array(
					'host' => trim( $host ),
					'key'  => 'wordpress-default',
				);
				break;
			case 'local.wordpress-trunk.dev' :
				$host = array(
					'host' => trim( $host ),
					'key'  => 'wordpress-trunk',
				);
				break;
			case 'src.wordpress-develop.dev' :
				$host = array(
					'host' => trim( $host ),
					'key'  => 'wordpress-develop',
					'path' => '/src',
				);
				break;
			case 'build.wordpress-develop.dev' :
				$host = array(
					'host' => trim( $host ),
					'key'  => 'wordpress-develop/build',
					'path' => '/build'
				);
				break;
		}

		return $host;
	}
}
// End vvv-dash-hosts.php