<?php

/**
 *
 * PHP version 5
 *
 * Created: 2/19/16, 4:13 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * plugin.php
 */

namespace vvv_dash\commands;

class plugin {

	public function __construct() {
		$this->_cache   = new \vvv_dash_cache();
		$this->vvv_dash = new \vvv_dashboard();
	}

	/**
	 * Returns the plugin list for the requested host
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:44 AM
	 *
	 * @param $get
	 *
	 * @return bool|string
	 */
	public function get_plugins( $get ) {
		if ( isset( $get['host'] ) && isset( $get['get_plugins'] ) ) {
			$host_path = $this->vvv_dash->get_host_path( $get['host'] );
			$host_info = $this->vvv_dash->set_host_info( $get['host'] );
			$plugins   = $this->get_plugins_data( $host_info['host'], $host_path );

			return $plugins;
		} else {
			return false;
		}
	}

	/**
	 * Get the hosts list of plugins and save to cache
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    11/19/15, 2:55 PM
	 *
	 * @param        $host
	 * @param string $path
	 *
	 * @return bool|string
	 */
	public function get_plugins_data( $host, $path = '' ) {

		if ( ( $plugins = $this->_cache->get( $host . '-plugins', VVV_DASH_PLUGINS_TTL ) ) == false ) {

			$plugins = shell_exec( 'wp plugin list --path=' . VVV_WEB_ROOT . '/' . $host . $path . ' --format=csv --debug ' );

			// Don't save unless we have data
			if ( $plugins ) {
				$status = $this->_cache->set( $host . '-plugins', $plugins );
			}
		}

		return $plugins;
	}

	public function version() {

	}

	public function create() {

	}


	public function install() {

	}

	public function update() {

	}
}
// End plugin.php