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
 * theme.php
 */

namespace vvv_dash\commands;

class theme {

	public function __construct() {

		$this->_cache   = new \vvv_dash_cache();
		$this->vvv_dash = new \vvv_dashboard();
	}

	/**
	 * Get the hosts list of themes and save to cache
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    11/19/15, 2:56 PM
	 *
	 * @param        $host
	 * @param string $path
	 *
	 * @return bool|string
	 */
	public function get_themes_data( $host, $path = '' ) {

		if ( ( $themes = $this->_cache->get( $host . '-themes', VVV_DASH_THEMES_TTL ) ) == false ) {

			$themes = shell_exec( 'wp theme list --path=' . VVV_WEB_ROOT . '/' . $host . $path . ' --format=csv' );

			// Don't save unless we have data
			if ( $themes ) {
				$status = $this->_cache->set( $host . '-themes', $themes );
			}
		}

		return $themes;
	}

	/**
	 * Returns the theme list for the requested host
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
	public function get_themes( $get ) {
		if ( isset( $get['host'] ) && isset( $get['get_themes'] ) ) {
			$host_path = $this->vvv_dash->get_host_path( $get['host'] );
			$host_info = $this->vvv_dash->set_host_info( $get['host'] );
			$themes    = $this->get_themes_data( $host_info['host'], $host_path );

			return $themes;
		} else {
			return false;
		}
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
// End theme.php