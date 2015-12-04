<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/2/15, 10:45 AM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * vvv-dash-hosts.php
 */

/**
 * Class vvv_dash_hosts
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
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
	 * Get the paths for each host and set an array
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
		if ( isset( $host_info['is_env'] ) && $host_info['is_env'] && isset( $host_info['env_path'] ) ) {
			if(sizeof($this->get_wp_starter_configs( $host_info ))) {
				return true;
			} else {
				return true;
			}

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

	public function get_wp_starter_configs( $host_info ) {

		$config_array = array();


		//$env_file = VVV_WEB_ROOT . '/' . $host_info['host'] . '/.env';

		$env_file  = $this->get_env_file( $host_info );
		$env_lines = file( $env_file['env_path'] );
		$lines     = array_splice( $env_lines, 0, 15 );
		$env_array = array();


		foreach ( $lines as $num => $line ) {
			if ( strstr( $line, "WORDPRESS_ENV=" )
			     || strstr( $line, 'DB_NAME=' )
			     || strstr( $line, 'DB_USER=' )
			     || strstr( $line, "DB_PASSWORD=" )
			) {
				switch ( $line ) {
					case strstr( $line, "WORDPRESS_ENV=" ) :
						$env_array['WORDPRESS_ENV'] = trim( explode( '=', $line )[1] );
						break;

					case strstr( $line, 'DB_NAME=' ) :
						$env_array['DB_NAME'] = trim( explode( '=', $line )[1] );
						break;

					case strstr( $line, 'DB_USER=' ) :
						$env_array['DB_USER'] = trim( explode( '=', $line )[1] );
						break;

					case strstr( $line, "DB_PASSWORD=" ) :
						$env_array['DB_PASSWORD'] = trim( explode( '=', $line )[1] );
						break;
				}
			}
		} // end foreach

		$config_array[ $host_info['host'] ] = $env_array;
		$vars                               = array();
		$file                               = VVV_WEB_ROOT . '/' . $host_info['host'] . '/' . $host_info['path'] . '/wp-config.php';
		$config_lines                       = file( $file );
		$array1                             = array_chunk( $config_lines, 70 )[1];
		$array2                             = array_chunk( $array1, 27 )[0];
		$env_sec                            = implode( PHP_EOL, $array2 );
		$env_array                          = explode( 'break;', $env_sec );
		$env                                = trim( $config_array[ $host_info['host'] ]['WORDPRESS_ENV'] );

		foreach ( $env_array as $key => $chunk ) {
			$chunk = str_replace(
				array(
					' */',
					"\$environment = getenv('WORDPRESS_ENV');",
					'switch ($environment) {',
					"\n\n\n",
					"\t",
					"  ",
					"defined('WP_DEBUG')",
					"defined('WP_DEBUG_DISPLAY')",
					"defined('WP_DEBUG_LOG')",
					"defined('SCRIPT_DEBUG')",
					"defined('SAVEQUERIES')",
					"or ",
					"default:",
				),
				'', $chunk );

			if ( strstr( $chunk, "case '$env':" ) ) {

				$str = str_replace( array(
					"case '$env':",
					' ',
					"define('",
					");",
					"'"
				), '', $chunk );

				$test = array_filter( explode( "\n", $str ) );
				foreach ( $test as $cl ) {
					$k = strstr( $cl, ',', true );
					$v = strstr( $cl, ',' );

					$vars[ $k ] = str_replace( ',', '', $v );
				} // end foreach

				$config_array[ $host_info['host'] ][ $env ] = $vars;
			}

		} // end foreach

		return $config_array;
	}

	public function is_env_site( $host_info ) {

		$env_path = $this->get_env_file($host_info);

		if ( isset($env_path['env_path']) && ! empty($env_path['env_path']) ) {
			return true;
		} else {
			return false;
		}
	}

	public function get_env_file( $host_info ) {
		global $vvv_dash_scan_paths;

		$env_file = array();
		$file     = VVV_WEB_ROOT . '/' . $host_info['host'] . '/.env';

		if ( file_exists( $file ) ) {
			$env_file['env_path'] = $file;
		} else {

			foreach ( $vvv_dash_scan_paths as $dir ) {

				$file = VVV_WEB_ROOT . '/' . $host_info['host'] . '/' . $dir . '/.env';

				if ( file_exists( $file ) ) {
					$env_file['env_path'] = $file;
				}

			} // end foreach
			unset( $dir );
		}

		return $env_file;
	}
}
// End vvv-dash-hosts.php