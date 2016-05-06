<?php

/**
 *
 * PHP version 5
 *
 * Created: 2/19/16, 4:14 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * host.php
 */

namespace vvv_dash\commands;
//use \vvv_dash;

use vvv_dash\cache;
use vvv_dash\dashboard;
use vvv_dash\vvv_dash_hosts;

class host extends vvv_dash_hosts {

	private $_cache;
	private $_vvv_dash;
	
	public function __construct() {
		$this->_cache    = new cache();
		$this->_vvv_dash = new dashboard();
	}

	/**
	 * Returns the host data
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:46 AM
	 *
	 * @param $path
	 *
	 * @return array|bool|string
	 */
	public function get_hosts( $path ) {
		if ( ( $hosts = $this->_cache->get( 'host-sites', VVV_DASH_HOSTS_TTL ) ) == false ) {

			$hosts  = $this->get_hosts_data( $path );
			$status = $this->_cache->set( 'host-sites', serialize( $hosts ) );
		}

		return $hosts;
	}

	/**
	 * Returns the host path
	 *
	 * @ToDO           needs to be updated with the path methods
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:46 AM
	 *
	 * @param $host
	 *
	 * @return string
	 */
	public function get_host_path( $host ) {

		$host_info = $this->set_host_info( $host );
		$is_env    = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;

		// WP Starter
		if ( $is_env ) {
			$host_path = '/public/wp';
		} else {
			// Normal WP
			$host_path = $host_info['path'];
		}

		return $host_path;
	}

	/**
	 * Create an array of the hosts from all of the VVV host files
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2014 ValidWebs.com
	 *
	 * Created:    5/23/14, 12:57 PM
	 *
	 * @param $path
	 *
	 * @return array
	 */
	public function get_hosts_data( $path ) {

		$array = array();
		$debug = array();
		$hosts = array();
		$wp    = array();
		$depth = VVV_DASH_SCAN_DEPTH;
		$site  = new \RecursiveDirectoryIterator( $path, \RecursiveDirectoryIterator::SKIP_DOTS );
		$files = new \RecursiveIteratorIterator( $site );
		if ( ! is_object( $files ) ) {
			return null;
		}
		$files->setMaxDepth( $depth );

		// Loop through the file list and find what we want
		foreach ( $files as $name => $object ) {

			if ( strstr( $name, 'vvv-hosts' ) && ! is_dir( 'vvv-hosts' ) ) {

				$lines = file( $name );
				$name  = str_replace( array( '../../', '/vvv-hosts' ), array(), $name );

				// read through the lines in our host files
				foreach ( $lines as $num => $line ) {

					// skip comment lines
					if ( ! strstr( $line, '#' ) && 'vvv.dev' != trim( $line ) ) {
						if ( 'vvv-hosts' == $name ) {
							switch ( trim( $line ) ) {
								case 'local.wordpress.dev' :
									$hosts['wordpress-default'] = array( 'host' => trim( $line ) );
									break;
								case 'local.wordpress-trunk.dev' :
									$hosts['wordpress-trunk'] = array( 'host' => trim( $line ) );
									break;
								case 'src.wordpress-develop.dev' :
									$hosts['wordpress-develop/src'] = array( 'host' => trim( $line ) );
									break;
								case 'build.wordpress-develop.dev' :
									$hosts['wordpress-develop/build'] = array( 'host' => trim( $line ) );
									break;
							}
						}
						if ( 'vvv-hosts' != $name ) {
							$hosts[ $name ] = array( 'host' => trim( $line ) );
						}
					}
				}
			}

			if ( strstr( $name, 'wp-config.php' ) ) {

				$config_lines = file( $name );
				$name         = str_replace( array( '../../', '/wp-config.php', '/htdocs' ), array(), $name );

				// read through the lines in our host files
				foreach ( $config_lines as $num => $line ) {

					// skip comment lines
					if ( strstr( $line, "define('WP_DEBUG', true);" )
					     || strstr( $line, 'define("WP_DEBUG", true);' )
					     || strstr( $line, 'define( "WP_DEBUG", true );' )
					     || strstr( $line, "define( 'WP_DEBUG', true );" )
					) {
						$debug[ $name ] = array(
							'path'  => $name,
							'debug' => 'true',
						);
					}
				}

				$wp[ $name ] = 'true';
			}
		}

		foreach ( $hosts as $key => $val ) {

			if ( array_key_exists( $key, $debug ) ) {
				if ( array_key_exists( $key, $wp ) ) {
					$array[ $key ] = $val + array( 'debug' => 'true', 'is_wp' => 'true' );
				} else {
					$array[ $key ] = $val + array( 'debug' => 'true', 'is_wp' => 'false' );
				}
			} else {
				if ( array_key_exists( $key, $wp ) ) {
					$array[ $key ] = $val + array( 'debug' => 'false', 'is_wp' => 'true' );
				} else {
					$array[ $key ] = $val + array( 'debug' => 'false', 'is_wp' => 'false' );
				}
			}

			$host_info = $this->set_host_info( $val["host"] );
			$is_env    = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;

			// wp core version --path=<path>
			if ( $is_env ) {
				$host_path = '/public/wp';
			} else {
				// Normal WP
				$host_path = $host_info['path'];
			}

			$wp_version               = shell_exec( 'wp core version --path=' . VVV_WEB_ROOT . '/' . $host_info['host'] . $host_path );
			$array[ $key ]['version'] = $wp_version;

			// Causes load issues do to each API call SO this can not be in a loop
			// @ToDo find a better way
			//$update_check             = shell_exec( 'wp core check-update --path=' . VVV_WEB_ROOT . '/' . $host_info['host'] . $host_path );
			//$array[ $key ]['update']  = $update_check;
		}

		$array['site_count'] = count( $hosts );

		return $array;
	}

	/**
	 * Sets an array containing the needed host info
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:45 AM
	 *
	 * @param $host
	 *
	 * @return array
	 */
	public function set_host_info( $host ) {

		$host_info = array();
		//$hosts     = new vvv_dash_hosts();

		// Are these default hosts
		$type = $this->check_host_type( $host );

		if ( isset( $type['key'] ) ) {

			if ( isset( $type['path'] ) ) {
				$host_info['host']    = $type['key'];
				$host_info['path']    = $type['path'];
				$host_info['content'] = '/wp-content';
			} else {
				$host_info['host']    = $type['key'];
				$host_info['path']    = '/';
				$host_info['content'] = '/wp-content';
			}

		} else {
			$host_info = $this->get_paths( $host );
		}

		$env_file              = $this->get_env_file( $host_info );
		$host_info['is_env']   = $this->is_env_site( $host_info );
		$host_info['env_path'] = ( isset( $env_file['env_path'] ) ) ? $env_file['env_path'] : '';

		return $host_info;
	}
	

	/**
	 * Display debug logs at a host level if available
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/6/16, 3:14 PM
	 *
	 */
	public function display_debug_logs() {

		if ( isset( $_GET['host'] ) ) {
			$wp_debug_log    = $this->_vvv_dash->get_wp_debug_log( $_GET );
			$debug_log_lines = ( isset( $wp_debug_log['lines'] ) ) ? $wp_debug_log['lines'] : false;
			$debug_log_path  = ( isset( $wp_debug_log['path'] ) ) ? $wp_debug_log['path'] : false;

			if ( $debug_log_path && file_exists( $debug_log_path ) && ! empty( $debug_log_lines ) ) {

				$close = '<a class="close" href="./">Close</a>';

				?><h4>Debug Log for
				<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

				?>
				<div class="wp-debug-log">
					<?php echo $debug_log_lines; ?>
				</div>
				<?php
			}
		}
	}
}
// End host.php