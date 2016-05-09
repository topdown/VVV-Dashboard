<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 11:37 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * standard_wp.php
 */

namespace vvv_dash\hosts;

use vvv_dash\host_interface;
use vvv_dash\hosts;
use vvv_dash\hosts_container;


class standard_wp extends hosts implements host_interface {

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
		parent::set_host_path( $hostname );
	}

	public function set_wp_path( $wp_path = '' ) {
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
		parent::set_composer_path();
	}

	public function set_env_path( $env_path = '' ) {
		parent::set_env_path();
	}

	public function is_standard_host() {
		$this->is_standard_host = 'false';
	}

	public function set_wp_content_path( $wp_content ) {
		$this->wp_content_path = $wp_content;
	}

	public function host_list() {
		$host_info = array();

		//print each file name
		foreach ( $this->host_directories as $key => $file ) {
			//check to see if the file is a folder/directory
			if ( is_dir( $file ) ) {
				$hostname = str_replace( '/srv/www/', '', $file );

				if ( substr( $hostname, 0, 1 ) === "_" ) {

					$this->set_hostname( $hostname );
					$this->set_host_path( $hostname );
					$this->domain          = $hostname;
					$this->public_dir      = '';
					$this->version         = 'N/A';
					$this->wp_path         = '';
					$this->wp_config_path  = '';
					$this->wp_content_path = '';
					$this->config_settings = '';
					$this->wp_is_installed = 'false';
					$this->is_wp_site      = 'false';
					$host_data             = $this->get_host_info();

					if ( ! in_array( $host_data['hostname'], $this->ignored_hosts )
					     && ! in_array( $host_data['hostname'], $this->default_hosts )
					) {
						$host_info[ $hostname ] = $host_data;
					}

				} else {

					$this->set_hostname( $hostname );
					$this->set_host_path( $hostname );
					$this->set_domain();
					$this->set_public_dir();
					$this->set_wp_path( $this->host_path . '/' . $this->public_dir );
					$this->set_wp_content_path( $this->host_path . '/' . $this->public_dir . '/wp-content' );
					$this->set_composer_path();
					$this->set_env_path();
					$this->is_wp_site = 'true';
					
					if ( empty( $this->env_path ) ) {
						//$this->get_composer_file();
						$this->set_wp_config_path();
						$this->set_config_settings();
						$this->set_version();
						$this->set_debug_log_path();

						$host_data = $this->get_host_info();


						if ( ! in_array( $host_data['hostname'], $this->ignored_hosts )
						     && ! in_array( $host_data['hostname'], $this->default_hosts )
						) {
							$key = (! empty( $this->domain ) && $this->domain != 'N/A') ? $this->domain : $hostname;
							$host_info[ $key ] = $host_data;

						}
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
		if ( file_exists( $this->wp_path . '/wp-config.php' ) ) {
			$this->wp_is_installed = 'true';
		}

		return $this->wp_is_installed;
	}


	protected function is_wp_site() {
		if ( file_exists( $this->host_path . '/vvv-hosts' ) ) {
			$this->is_wp_site = 'true';
		}
	}

}

// End standard_wp.php