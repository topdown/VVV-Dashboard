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
					'key'  => 'wordpress-develop',
					'path' => '/build'
				);
				break;
		}

		return $host;
	}


	/**
	 * Allows setting of alternate wp-content path
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/2/15, 11:51 AM
	 *
	 * @param $path
	 *
	 * @return array|bool|string
	 */
	public function set_content_path( $path ) {
		global $vvv_dash_wp_content_paths;

		$paths      = array();
		$wp_content = $path . '/wp-content';

		if ( is_dir( $wp_content ) ) {
			$paths = '/wp-content';
		} else {

			foreach ( $vvv_dash_wp_content_paths as $key => $new_path ) {
				if ( is_dir( $path . '/' . $new_path ) ) {
					$paths = '/' . $new_path;
				} else {
					$paths = false;
				}
			} // end foreach
		}

		return $paths;
	}


	/**
	 *
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/2/15, 11:32 AM
	 *
	 * @param $host
	 *
	 * @return array
	 */
	public function get_paths( $host ) {

		$host_info         = array();
		$host              = strstr( $host, '.', true );
		$path              = VVV_WEB_ROOT . '/' . $host . '/htdocs';
		$host_info['host'] = $host;

		if ( is_dir( $path ) ) {
			$host_info['path']    = '/htdocs';
			$host_info['content'] = $this->set_content_path( $path );

		} else {
			global $vvv_dash_scan_paths;

			// Loop through alternate paths
			foreach ( $vvv_dash_scan_paths as $key => $path ) {

				// Test alternate paths
				$path_test = VVV_WEB_ROOT . '/' . $host . '/' . $path;
				if ( is_dir( $path_test ) ) {
					$host_info['path']    = $path;
					$host_info['content'] = $this->set_content_path( $path_test );
				} else {
					// Something is wrong and we have no paths
					$host_info['path']    = false;
					$host_info['content'] = false;
				}
			}
		}

		return $host_info;
	}

	/**
	 * Check if we have a normal wp-config.php
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/2/15, 1:48 PM
	 *
	 * @param $host_info
	 *
	 * @return bool
	 */
	public function wp_config_exists( $host_info ) {
		
		// Custom host
		if ( isset( $host_info['is_env'] ) && $host_info['is_env'] ) {
			return false;
		} else {
			// Normal host

			$file = VVV_WEB_ROOT . '/' . $host_info['host'] . $host_info['path'] . '/wp-config.php';

			if ( file_exists( $file ) ) {
				return true;
			} else {
				return false;
			}
		}

	}


	public function is_env_site( $host_info ) {

		$file = VVV_WEB_ROOT . '/' . $host_info['host'] . '/.env';

		if ( file_exists( $file ) ) {
			$is_env = true;
		} else {
			$is_env = false;
		}

		return $is_env;
	}
}
// End vvv-dash-hosts.php