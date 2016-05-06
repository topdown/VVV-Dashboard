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
 * database.php
 */

namespace vvv_dash\commands;

/**
 * Actions that happen on the WP databases
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Class database
 * @package        vvv_dash\commands
 */
class database {


	public function __construct() {

	}

	/**
	 *
	 * @ToDo           get rid of $path param
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/20/16, 1:54 AM
	 *
	 * @param $path
	 * @param $file_name
	 *
	 * @return bool|string
	 */
	public function backup( $path, $file_name ) {
		$export = shell_exec( 'wp db export --add-drop-table --path=' . $path . ' ' . $file_name );

		if ( $export ) {
			return $export;
		} else {
			return false;
		}
	}

	/**
	 *
	 * @ToDo           get rid of $path param
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/20/16, 1:55 AM
	 *
	 * @param $path
	 * @param $file
	 *
	 * @return bool|string
	 */
	public function roll_back( $path, $file ) {
		$status = shell_exec( 'wp db import --path=' . $path . ' ' . urldecode( $file ) );

		if ( $status ) {
			return $status;
		} else {
			return false;
		}
	}

	// @ToDo needs lots of work here, to much happening
	public function migrate() {

		$host      = $_GET['host'];
		$domain    = ( isset( $_GET['domain'] ) ) ? $_GET['domain'] : false;
		$vvv_dash  = new \vvv_dashboard();
		$host_info = $vvv_dash->set_host_info( $host );
		$host_path = VVV_WEB_ROOT . '/' . $host_info['host'] . $host_info['path'];

		$cmd     = 'wp search-replace --url=' . $host . ' ' . $host . ' ' . $domain . ' --path=' . $host_path;
		$migrate = shell_exec( $cmd );

		if ( $migrate ) {

			$file_name = 'dumps/migrated-' . $domain . '_' . date( 'm-d-Y_g-i-s', time() ) . '.sql';
			$status    = $this->backup( $host, $file_name );

			if ( $status ) {
				return $status;
			} else {
				return vvv_dash_error( 'ERROR: Migration data dump failed.' );
			}

		} else {
			return vvv_dash_error( 'ERROR: Something went wrong, the migration did not happen.' );
		}
	}

	public function delete_backup( $file ) {
		if ( file_exists( $file ) ) {

			// @ToDo verify with the user (Are you sure!)
			unlink( $file );

			return $file . ' was deleted.';
		}

		return vvv_dash_error( 'ERROR: file does not exist' );
	}

}
// End database.php