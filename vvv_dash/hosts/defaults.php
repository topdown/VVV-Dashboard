<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/7/16, 3:01 AM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * defaults.php
 */

namespace vvv_dash\hosts;

use vvv_dash\host_interface;
use vvv_dash\hosts;
use vvv_dash\hosts_container;

class defaults extends hosts implements host_interface {


	public function __construct() {
		parent::__construct();
	}

	public function load_hosts() {
		return hosts_container::set_host_list( $this->get_host_list() );
	}

	public function set_domain( $domain ) {
		$this->domain = $domain;
	}

	public function set_hostname( $hostname ) {
		$this->hostname = $hostname;
	}

	public function set_host_path( $hostname ) {
		$this->host_path = $hostname;
	}

	public function set_wp_path( $wp_path ) {
		$this->wp_path = $wp_path;
	}

	public function set_version() {
		parent::set_version();
	}

	public function set_debug_log_path( $log_file = '' ) {
		parent::set_debug_log_path( $log_file );
	}

	public function set_wp_config_path( $wp_config_file = '' ) {
		parent::set_wp_config_path( $wp_config_file );
	}

	public function set_config_settings() {
		parent::set_config_settings();
	}

	public function set_composer_path( $composer_path = '' ) {
		$this->composer_path = '';
	}

	public function set_env_path( $env_path = '' ) {
		$this->env_path = '';
	}

	public function is_standard_host() {
		$this->is_standard_host = 'false';
	}


	public function host_list() {

		$defaults = array(
			'wordpress-default' => array(
				'hostname'        => 'wordpress-default',
				'domain'          => 'local.wordpress.dev',
				'web_root'        => '/srv/www',
				'host_path'       => '/srv/www/wordpress-default',
				'public_dir'      => 'wordpress-default',
				'wp_path'         => '/srv/www/wordpress-default',
				'wp_content_path' => '/srv/www/wordpress-default/wp-content',
				'composer_path'   => '',
				'wp_config_path'  => '/srv/www/wordpress-default/wp-config.php',
				'env_path'        => '',
				'debug_log'       => '',
				'wp_is_installed' => 'true',
				'is_wp_site'      => 'true',
			),

			'wordpress-trunk' => array(
				'hostname'        => 'wordpress-trunk',
				'domain'          => 'local.wordpress-trunk.dev',
				'web_root'        => '/srv/www',
				'host_path'       => '/srv/www/wordpress-trunk',
				'public_dir'      => 'wordpress-trunk',
				'wp_path'         => '/srv/www/wordpress-trunk',
				'wp_content_path' => '/srv/www/wordpress-trunk/wp-content',
				'composer_path'   => '',
				'wp_config_path'  => '/srv/www/wordpress-trunk/wp-config.php',
				'env_path'        => '',
				'debug_log'       => '',
				'wp_is_installed' => 'true',
				'is_wp_site'      => 'true',
			),

			'wordpress-develop' => array(
				'hostname'        => 'wordpress-develop',
				'domain'          => 'src.wordpress-develop.dev',
				'web_root'        => '/srv/www',
				'host_path'       => '/srv/www/wordpress-develop/src',
				'public_dir'      => 'wordpress-develop',
				'wp_path'         => '/srv/www/wordpress-develop/src',
				'wp_content_path' => '/srv/www/wordpress-develop/src/wp-content',
				'composer_path'   => '',
				'wp_config_path'  => '/srv/www/wordpress-develop/src/wp-config.php',
				'env_path'        => '',
				'debug_log'       => '',
				'wp_is_installed' => 'true',
				'is_wp_site'      => 'true',
			),

			'build/wordpress-develop' => array(
				'hostname'        => 'build.wordpress-develop',
				'domain'          => 'build.wordpress-develop.dev',
				'web_root'        => '/srv/www',
				'host_path'       => '/srv/www/wordpress-develop/build',
				'public_dir'      => 'build',
				'wp_path'         => '/srv/www/wordpress-develop/build',
				'wp_content_path' => '/srv/www/wordpress-develop/build/wp-content',
				'composer_path'   => '',
				'wp_config_path'  => '/srv/www/wordpress-develop/build/wp-config.php',
				'env_path'        => '',
				'debug_log'       => '',
				'wp_is_installed' => 'true',
				'is_wp_site'      => 'true',
			)
		);

		$host_info = array();

		foreach ( $defaults as $host => $values ) {

			$this->hostname = $host;

			foreach ( $values as $key => $value ) {

				if ( $key == 'domain' ) {
					$this->set_domain( $value );
				}

				if ( $key == 'public_dir' ) {
					$this->public_dir = $value;
				}

				if ( $key == 'host_path' ) {
					if ( is_dir( $value ) ) {
						$this->set_host_path( $value );
					}
				}

				if ( $key == 'wp_path' ) {
					if ( is_dir( $value ) ) {
						$this->set_wp_path( $value );
					}
				}

				if ( $key == 'wp_content_path' ) {
					if ( is_dir( $value ) ) {
						$this->wp_content_path = $value;
					}
				}

				if ( $key == 'wp_config_path' ) {
					if ( file_exists( $value ) ) {
						$this->set_wp_config_path( $value );
					}
				}

				if ( $key == 'debug_log' ) {
					if ( file_exists( $this->wp_content_path . '/debug.log' ) ) {
						$this->debug_log = $this->wp_content_path . '/debug.log';
					} else {
						$this->debug_log = '';
					}
				}
				$this->is_wp_site = 'true';
				$this->set_version();
				$this->set_debug_log_path();
				$this->set_wp_config_path();
				$this->set_config_settings();
				$this->wp_is_installed();
			} // end foreach

			$host_data         = $this->get_host_info();
			$key               = ( ! empty( $this->domain ) && $this->domain != 'N/A' ) ? $this->domain : $this->hostname;
			$host_info[ $key ] = $host_data;

		} // end foreach

		return $host_info;
		//$this->add_hosts( $defaults );

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
		if ( file_exists( $this->wp_path . '/wp-config.php' ) ) {
			$this->wp_is_installed = 'true';
		}

		return $this->wp_is_installed;
	}


}

// End defaults.php