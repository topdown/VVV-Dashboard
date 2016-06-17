<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/19/15, 9:50 AM
 *
 * LICENSE:
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * vvv-dash-cache.php
 */

namespace vvv_dash;

/**
 * Cache class for making load intensive items a little better on performance
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Class vvv_dash_cache
 */
class cache {

	private $_cache_path = 'cache/';
	private $_skip_files = array( '.', '..', 'index.php', '.DS_Store' );

	/**
	 * vvv_dash_cache constructor.
	 */
	function __construct() {

		$this->_cache_path = VVV_WEB_ROOT . '/default/dashboard/cache/';

		if( ! is_dir($this->_cache_path)) {
			mkdir($this->_cache_path, '0777');
		}
	}


	public function get( $file, $required_ttl ) {

		$file_info = $this->_check_file_status( $file );
		if ( $file_info ) {

			$time = time() - $file_info['time'];

			// If old purge the file
			if($time >= $required_ttl) {
				$this->purge($file);
			}

			// Recheck file status in case we purged it
			if(file_exists($this->_cache_path . $file_info['file'])) {
				$data = file_get_contents( $this->_cache_path . $file_info['file'] );

				return $data;
			} else {
				return false;
			}

		}
		return false;
	}

	/**
	 * Set the cache data to file
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    11/19/15, 10:06 AM
	 *
	 * @param $file string
	 * @param $data string
	 *
	 * @return int
	 */
	public function set( $file, $data ) {

		$cache_file = $this->_cache_path . $file . '-' . time() . '.txt';
		$status     = $this->_check_file_status( $file );
		
		if ( ! $status ) {

			$status = file_put_contents( $cache_file, $data );

			return $status;
		}

		return false;
	}

	public function purge( $name ) {

		$cache_exists = glob( $this->_cache_path . '*' . $name . '-*' );

		if ( $cache_exists ) {
			if(is_array($cache_exists)) {
				$count = count($cache_exists);
				foreach ( $cache_exists as $file ) {
					unlink($file);
				} // end foreach
				return $count;
			} else {
				return unlink( $cache_exists[0] );
			}
		}

		return false;
	}

	private function _check_file_status( $name ) {

		$list = $this->_cache_list( true );

		if ( array_key_exists( $name, $list ) ) {
			return $list[ $name ];
		}

		return false;
	}

	/**
	 * List all files in cache spit into details
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    11/19/15, 10:19 AM
	 *
	 * @param bool $prefix_only
	 *
	 * @return array
	 */
	private function _cache_list( $prefix_only = false ) {

		$new_list = array();
		$cache    = $this->_dir_to_array( $this->_cache_path );

		if ( $prefix_only ) {
			foreach ( $cache as $file ) {
				$part                     = explode( '-', $file );
				$key                      = $part[0] . '-' . $part[1];
				$new_list[ $key ]['file'] = $file;
				$new_list[ $key ]['host'] = $part[0];
				$new_list[ $key ]['type'] = $part[1];
				$new_list[ $key ]['time'] = str_replace( '.txt', '', $part[2] );
			} // end foreach

		}

		if ( sizeof( $new_list ) ) {
			return $new_list;
		}

		return $cache;
	}

	private function _dir_to_array( $dir ) {

		$result = array();

		$cdir = scandir( $dir );

		foreach ( $cdir as $key => $value ) {

			if ( ! in_array( $value, $this->_skip_files ) ) {

				if ( is_dir( $dir . DIRECTORY_SEPARATOR . $value ) ) {
					$result[ $value ] = $this->_dir_to_array( $dir . DIRECTORY_SEPARATOR . $value );
				} else {
					$result[] = $value;
				}
			}
		}

		return $result;
	}

	/**
	 *
	 */
	function __destruct() {
		// TODO: Implement __destruct() method.
	}

}
// End vvv-dash-cache.php