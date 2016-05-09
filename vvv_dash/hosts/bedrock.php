<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 11:39 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * bedrock.php
 */

namespace vvv_dash\hosts;

use vvv_dash\host_interface;
use vvv_dash\hosts;

class bedrock extends hosts implements host_interface {

	public function __construct() {
		parent::__construct();
	}

	public function load_hosts() {
		//return hosts_container::set_host_list( $this->get_host_list() );
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

	public function set_debug_log_path( $log_file ) {
		$this->debug_log = $log_file;
	}

	public function set_wp_config_path( $wp_config_file ) {
		$this->wp_config_path = $wp_config_file;
	}

	public function set_config_settings() {
		parent::set_config_settings();
	}
}


// End bedrock.php