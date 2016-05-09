<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 11:33 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * host_interface.php
 */

namespace vvv_dash;

interface host_interface {

	public function load_hosts();

	public function set_hostname( $hostname );

	public function set_domain($domain);

	public function set_host_path( $hostname );

	public function set_public_dir();

	public function set_wp_path( $wp_path );

	public function set_version();

	public function set_debug_log_path( $log_file );

	public function set_wp_config_path( $wp_config_file );

	public function set_config_settings();

	public function set_composer_path(  );

	public function set_env_path(  );

	public function host_list();

	public function is_standard_host();
}

// End host_interface.php