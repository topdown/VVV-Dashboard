<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 11:34 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * hosts.php
 */

namespace vvv_dash;

include_once 'host_interface.php';

class hosts implements host_interface {

	protected $hostname;
	protected $domain;
	protected $web_root         = VVV_WEB_ROOT;
	protected $host_path;
	protected $public_dir;
	protected $wp_path;
	protected $wp_content_path;
	protected $version;
	protected $composer_path    = '';
	protected $wp_config_path   = '';
	protected $env_path         = '';
	protected $wp_is_installed  = 'false';
	protected $is_wp_site       = 'false';
	protected $debug_log        = '';
	protected $config_settings  = '';
	protected $host_directories = array();
	protected $ignored_hosts    = array( 'wp-cli', 'phpcs', 'default' );
	protected $default_hosts    = array( 'wordpress-default', 'wordpress-develop', 'wordpress-trunk' );

	protected $host_list = array();
	protected $is_standard_host;

	public function __construct() {
		$this->host_directories();

		return $this->host_directories;
	}

	public function load_hosts() {
		return hosts_container::set_host_list( $this->get_host_list() );
	}


	private function host_directories() {
		$directories = glob( VVV_WEB_ROOT . "/*" );
		foreach ( $directories as $host_path ) {
			$hostname = str_replace( '/srv/www/', '', $host_path );
			if ( is_dir( $host_path )
			     && ! in_array( $hostname, $this->ignored_hosts )
			     && ! in_array( $hostname, $this->default_hosts )
			) {
				$this->host_directories[] = $host_path;
			}
		} // end foreach

		return $this->host_directories;
	}

	public function set_domain( $domain = '' ) {
		//$this->domain = $domain;
		if(! empty($domain)) {
			$this->domain = $domain;
		} elseif ( file_exists( $this->host_path . '/vvv-hosts' ) ) {
			$domain       = file_get_contents( $this->host_path . '/vvv-hosts' );
			$this->domain = trim( $domain );
		} else {
			$this->domain = 'N/A';
		}
		return $this->domain;
	}

	public function set_hostname( $hostname ) {
		$this->hostname = $hostname;
	}

	public function set_host_path( $hostname ) {
		return $this->host_path = $this->web_root . '/' . $hostname;
	}

	public function set_public_dir() {
		if ( file_exists( $this->host_path . '/wp-cli.yml' ) ) {
			$path             = file_get_contents( $this->host_path . '/wp-cli.yml' );
			$this->public_dir = str_replace( 'path: ', '', $path );
		} else {
			$this->public_dir = '';
		}
	}

	public function set_wp_path( $wp_path = '' ) {
		$this->wp_path = $wp_path;
	}

	public function set_debug_log_path( $log_file = '' ) {
		if ( file_exists( $this->wp_content_path . '/debug.log' ) ) {

			$this->debug_log = $this->wp_content_path . '/debug.log';
		} else {
			$this->debug_log = $log_file;
		}
		
	}

	public function set_wp_config_path( $wp_config_file_path = '' ) {

		// get wp-config
		$config_file = $this->wp_path . '/wp-config.php';
		if ( file_exists( $config_file ) ) {
			$this->wp_config_path = $config_file;
			$this->wp_is_installed = 'true';
		} else {
			$this->wp_config_path = '';
			$this->wp_is_installed = 'false';
		}

	}

	public function set_composer_path() {

		if ( file_exists( $this->host_path . '/composer.json' ) ) {
			$this->composer_path = $this->host_path . '/composer.json';

			return $this->composer_path;
		}

		// if for some screwy reason they have it in the protected path
		if ( file_exists( $this->host_path . '/' . $this->public_dir . '/composer.json' ) ) {
			$this->composer_path = $this->host_path . '/' . $this->public_dir . '/composer.json';

			return $this->composer_path;
		}

		return $this->composer_path = '';
	}

	public function set_env_path() {

		if ( file_exists( $this->host_path . '/.env' ) ) {
			$this->env_path = $this->host_path . '/.env';

			return $this->env_path;
			
		} elseif ( file_exists( $this->host_path . '/' . $this->public_dir . '/.env' ) ) {
			$this->env_path = $this->host_path . '/' . $this->public_dir . '/.env';

			return $this->env_path;
		} else {
			return $this->env_path = '';
		}
	}

	public function is_standard_host() {
		$this->is_standard_host = 'false';
	}

	public function set_config_settings() {
		// get wp-config
		if ( file_exists( $this->wp_config_path ) ) {
			$this->config_settings = $this->get_wp_config_data();
		} else {
			$this->config_settings =  '';
		}
	}

	public function set_version() {
		if ( file_exists( $this->wp_path . '/wp-includes/version.php' ) ) {

			$version_file = $this->wp_path . '/wp-includes/version.php';
			$file_lines   = file( $version_file );
			$lines        = array_splice( $file_lines, 0, 15 );
			$version      = 'false';

			foreach ( $lines as $num => $line ) {
				if ( strstr( $line, '$wp_version =' ) ) {
					$version = str_replace( array( "'", ";" ), '', trim( explode( ' = ', $line )[1] ) );
				}
			}
			$this->version = $version;
		} else {
			$this->version = 'N/A';
		}
	}

	public function host_list() {
		$host_info = array();

		return $host_info;
	}

	protected function get_host_list() {

		$host_list = $this->host_list();

		return $host_list;
	}
	

	protected function get_host_info() {

		$data = array(
			'hostname'        => $this->hostname,
			'domain'          => $this->domain,
			'web_root'        => $this->web_root,
			'host_path'       => $this->host_path,
			'public_dir'      => $this->public_dir,
			'wp_path'         => $this->wp_path,
			'wp_content_path' => $this->wp_content_path,
			'composer_path'   => $this->composer_path,
			'wp_config_path'  => $this->wp_config_path,
			'env_path'        => $this->env_path,
			'debug_log'       => $this->debug_log,
			'wp_is_installed' => $this->wp_is_installed,
			'is_wp_site'      => $this->is_wp_site,
			'wp_version'      => $this->version,
			'config_settings' => $this->config_settings,
		);

		return $data;
	}


	protected function is_wp_site() {
		$this->is_wp_site = 'false';
	}


	protected function get_wp_config_data() {
		$config_array = array();
		$lines        = file( $this->wp_config_path );

		// read through the lines in our host files
		foreach ( $lines as $num => $line ) {

			if ( strstr( $line, 'DB_NAME' ) ) {
				$config_array['DB_NAME'] = $line;
			}

			if ( strstr( $line, 'DB_USER' ) ) {
				$config_array['DB_USER'] = $line;
			}

			if ( strstr( $line, 'DB_PASSWORD' ) ) {
				$config_array['DB_PASSWORD'] = $line;
			}

			if ( strstr( $line, 'WP_DEBUG"' ) || strstr( $line, "WP_DEBUG'" ) ) {
				$config_array['WP_DEBUG'] = $line;
			}

			if ( strstr( $line, 'WP_DEBUG_LOG' ) ) {
				$config_array['WP_DEBUG_LOG'] = $line;
			}

			if ( strstr( $line, 'SCRIPT_DEBUG' ) ) {
				$config_array['SCRIPT_DEBUG'] = $line;
			}

			if ( strstr( $line, 'WP_DEBUG_DISPLAY' ) ) {
				$config_array['WP_DEBUG_DISPLAY'] = $line;
			}

			if ( strstr( $line, 'SAVEQUERIES' ) ) {
				$config_array['SAVEQUERIES'] = $line;
			}

			// define('MULTISITE', true);
			if ( strstr( $line, 'MULTISITE' ) ) {
				$config_array['MULTISITE'] = $line;
			}
		} // end foreach

		$settings = array();

		foreach ( $config_array as $key => $config ) {

			if ( strstr( $config, '//' ) ) {
				$settings[ $key ] = 'false';
			} else {
				$search = array(
					' ',
					'define',
					'(',
					')',
					',',
					"'",
					$key,
					';'
				);
				//$settings[ $key ] = $config;
				$settings[ $key ] = trim( str_replace( $search, '', $config ) );
			}


		} // end foreach
		unset( $config );

		return $settings;
	}


	/**
	 * Check to see if WP is actually installed
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/7/16, 3:15 AM
	 *
	 * @return string
	 */
	protected function wp_is_installed() {
		return $this->wp_is_installed = 'false';
	}

//	protected function get_public_dir() {
//		if ( file_exists( $this->host_path . '/wp-cli.yml' ) ) {
//			$path             = file_get_contents( $this->host_path . '/wp-cli.yml' );
//			$this->public_dir = str_replace( 'path: ', '', $path );
//		}
//	}
//
//	protected function get_path() {
//		return $this->host_path = $this->web_root . '/' . $this->hostname;
//	}
//
//	protected function get_domain() {
//
//	}


	/**
	 * Last effort check to set paths
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/7/16, 3:10 AM
	 *
	 * @return string
	 */
	//	protected function set_custom_wp_content() {
	//
	//		if ( empty( $this->wp_content_path ) ) {
	//
	//			$check_content_paths = paths::get_wp_content_paths();
	//
	//			foreach ( $check_content_paths as $key => $path ) {
	//
	//				foreach ( $path as $dir ) {
	//					if ( is_dir( $this->host_path . '/' . $this->public_dir . '/' . $dir ) ) {
	//						$this->wp_content_path = $this->host_path . '/' . $this->public_dir . '/' . $dir;
	//					}
	//				} // end foreach
	//				unset( $dir );
	//			} // end foreach
	//			unset( $path );
	//
	//			return $this->wp_content_path;
	//
	//		}
	//	}

	/**
	 * Last effort check to set paths
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/7/16, 3:11 AM
	 *
	 */
	//	protected function set_custom_wp_path() {
	//
	//		if ( empty( $this->wp_path ) ) {
	//
	//			$check_scan_paths = paths::get_scan_paths();
	//
	//			foreach ( $check_scan_paths as $key => $path ) {
	//
	//				foreach ( $path as $dir ) {
	//					if ( is_dir( $this->host_path . '/' . $dir ) ) {
	//						$this->wp_path = $this->host_path . '/' . $dir;
	//					}
	//				} // end foreach
	//				unset( $dir );
	//			} // end foreach
	//			unset( $path );
	//
	//			return $this->wp_path;
	//		}
	//	}


}

// End hosts.php