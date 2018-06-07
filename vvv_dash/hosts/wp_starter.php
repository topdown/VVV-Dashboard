<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 11:38 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * wp_starter.php
 */

namespace vvv_dash\hosts;

use vvv_dash\host_interface;
use vvv_dash\hosts;
use vvv_dash\hosts_container;

class wp_starter extends hosts implements host_interface {

	public function __construct() {
		parent::__construct();

	}

	public function load_hosts() {
		return hosts_container::set_host_list( $this->get_host_list() );
	}

	public function set_domain( $domain = '' ) {
		parent::set_domain( $domain );
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
		}
	}

	public function set_wp_path( $wp_path = ''  ) {
		$this->wp_path = $wp_path;
	}

	public function set_version() {
		parent::set_version();
	}

	public function set_debug_log_path( $log_file = '' ) {

		if ( file_exists( $this->wp_content_path . '/debug.log' ) ) {

			$this->debug_log = $this->wp_content_path . '/debug.log';
		} else {
			$this->debug_log = '';
		}
	}

	public function set_wp_config_path( $wp_config_file_path = '' ) {
		if ( ! empty( $this->composer_path ) ) {

			$config_file = $this->host_path . '/' . $this->public_dir . '/wp-config.php';
			if ( file_exists( $config_file ) ) {
				$this->wp_config_path  = $config_file;
				$this->wp_is_installed = 'true';
				$this->is_wp_site      = 'true';
			}

		}
	}

	public function set_config_settings() {
		$this->config_settings = $this->get_wp_config_data();
	}

	public function set_composer_path() {
		parent::set_composer_path();
	}

	public function set_env_path() {
		parent::set_env_path();
	}

	public function is_standard_host() {
		$this->is_standard_host = 'false';
	}

	public function host_list() {
		$host_info = array();

		//print each file name
		foreach ( $this->host_directories as $key => $file ) {
			//check to see if the file is a folder/directory
			if ( is_dir( $file ) ) {
				$hostname = str_replace( '/srv/www/', '', $file );

				$this->set_hostname( $hostname );
				$this->set_host_path( $hostname );
				$this->set_domain();
				$this->set_public_dir();
				$this->set_composer_path();
				$this->set_env_path();

				if ( ! empty( $this->composer_path ) && ! empty( $this->env_path ) ) {

					$this->get_composer_file();
					$this->set_wp_config_path();
					$this->set_config_settings();
					$this->set_version();
					$this->set_debug_log_path();

					$host_data = $this->get_host_info();

					if ( ! in_array( $host_data['hostname'], $this->ignored_hosts )
					     && ! in_array( $host_data['hostname'], $this->default_hosts )
					) {
						$key               = ( ! empty( $this->domain ) && $this->domain != 'N/A' ) ? $this->domain : $hostname;
						$host_info[ $key ] = $host_data;

					}
				}

			}
		}

		return $host_info;
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

		// wpstarter
		if ( file_exists( $this->host_path . '/' . $this->public_dir . '/wp-config.php' ) ) {
			$this->wp_is_installed = 'true';
		}

		return $this->wp_is_installed;
	}

	/**
	 * If these are env hosts they have composer installs
	 * So we can use the info from composer.json
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/7/16, 3:15 AM
	 *
	 */
	protected function get_composer_file() {

		// Check common path for a wp composer install
		if ( file_exists( $this->composer_path ) ) {

			$json = json_decode( file_get_contents( $this->composer_path ), true );

			if ( isset( $json['extra']['wordpress-install-dir'] ) ) {

				if ( is_dir( $this->host_path . '/' . $json['extra']['wordpress-install-dir'] ) ) {

					$this->wp_path = $this->host_path . '/' . $json['extra']['wordpress-install-dir'];

				} elseif ( is_dir( $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'] ) ) {

					$this->wp_path = $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'];

				} elseif ( is_dir( $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'] ) ) {

					$this->wp_path = $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'];

				} else {
					$this->wp_path = '';
				}
			}

			if ( isset( $json['extra']['wordpress-content-dir'] ) ) {

				if ( is_dir( $this->host_path . '/' . $json['extra']['wordpress-content-dir'] ) ) {
					$this->wp_content_path = $this->host_path . '/' . $json['extra']['wordpress-content-dir'];
				} else {
					$this->wp_content_path = '';
				}
			} elseif ( is_dir( $this->host_path . '/' . $this->public_dir . '/wp-content' ) ) {
				$this->wp_content_path = $this->host_path . '/' . $this->public_dir . '/wp-content';
			} else {
				$this->wp_content_path = '';
			}

		}

	}

	protected function get_wp_config_data() {

		$config_array = array();


		//$env_file = VVV_WEB_ROOT . '/' . $host_info['host'] . '/.env';

		$env_lines = file( $this->env_path );
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

		$config_array = $env_array;
		$vars         = array();
		$file         = $this->wp_config_path;
		$config_lines = file( $file );
		$array1       = array_chunk( $config_lines, 70 )[1];
		$array2       = array_chunk( $array1, 27 )[0];
		$env_sec      = implode( PHP_EOL, $array2 );
		$env_array    = explode( 'break;', $env_sec );
		$env          = trim( $config_array['WORDPRESS_ENV'] );

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

				$config_array = $vars;
			}

		} // end foreach

		return $config_array;
	}

}


// End wp_starter.php