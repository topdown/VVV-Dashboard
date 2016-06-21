<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 1:56 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * favs.php
 */

namespace vvv_dash\commands;

use \vvv_dash;

class favs extends host {

//	public function __construct() {
//
//		//parent::__construct();
//		//$this->_cache    = new vvv_dash\cache();
//		//$this->_vvv_dash = new vvv_dash\dashboard();
//		//$this->_hosts    = new host();
//	}

	/**
	 * Install selected favorite plugins or themes from list
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/16/15, 11:16 AM
	 *
	 * @param $post
	 *
	 * @param $type
	 *
	 * @return bool|string
	 */
	public function install_fav_items( $post, $type ) {

		//$path      = $this->get_host_path( $post['host'] );
		//$host_info = $this->set_host_info( $post['host'] );
		//$path      = VVV_WEB_ROOT . '/' . $host_info['host'] . $path;
		$items     = ( isset( $post['checkboxes'] ) ) ? $post['checkboxes'] : false;
		$install   = array();

		if ( $items && is_array( $items ) ) {
			foreach ( $items as $key => $item ) {
				$install[] = shell_exec( 'wp ' . $type . ' install ' . $item . ' --activate --path=' . $this->host_info['wp_path'] . ' --debug' );
			} // end foreach

			return implode( '<br /><br />', $install );
		} else {
			return false;
		}
	}

	public function get_fav_list( $file_path ) {

		if ( ! file_exists( $file_path ) ) {
			return '';
		};

		$content    = file_get_contents( $file_path );
		$content    = explode( "\n", $content );
		$content    = array_filter( $content );
		$checkboxes = array();

		foreach ( $content as $item ) {
			$checkboxes[] = '<p><input type="checkbox" name="checkboxes[]" value="' . $item . '"/> &nbsp; <label> ' . $item . '</label></p>';
		} // end foreach
		unset( $item );

		return implode( '', $checkboxes );
	}
}

// End favs.php
