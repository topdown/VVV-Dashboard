<?php

/**
 *
 * PHP version 5
 *
 * Created: 2/19/16, 6:03 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * version.php
 */

namespace vvv_dash\commands;
use \vvv_dash;

/**
 * VVV Dashboard version handling
 *
 * @ToDo figure out what to do with get_external_data() used here from functions.php
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Class version
 * @package        vvv_dash\commands
 */
class version {
	/**
	 * The version er are using
	 *
	 * @property  $_version
	 */
	private $_version;

	/**
	 * Just in case we move VVV Dashboard later
	 *
	 * @property string $_repo_user
	 */
	private $_repo_user = 'topdown';


	public function __construct() {

		$this->set_version();

	}

	/**
	 * Set the version we are using
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/19/16, 5:31 PM
	 *
	 */
	private function set_version() {
		$this->_version = VVV_DASH_VERSION;
	}


	/**
	 * Collect the version info from the repo and cache it.
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/19/16, 5:30 PM
	 *
	 * @return bool|mixed|string
	 */
	private function get_remote_version() {

		$cache = new vvv_dash\cache();

		if ( ( $remote_version = $cache->get( 'version-cache', VVV_DASH_THEMES_TTL ) ) == false ) {

			$url            = 'https://raw.githubusercontent.com/' . $this->_repo_user . '/VVV-Dashboard/master/version.txt';
			$remote_version = get_external_data( $url );
			// Don't save unless we have data
			if ( $remote_version && ! strstr( $remote_version, 'Error' ) ) {
				$status = $cache->set( 'version-cache', $remote_version );
			}
		}

		return $remote_version;
	}


	/**
	 * Compare the versions and return
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/19/16, 5:43 PM
	 *
	 * @return bool
	 */
	public function check_version() {
		if ( $this->_version < $this->get_remote_version() ) {
			$update = true;
		} else {
			$update = false;
		}

		return $update;
	}

	/**
	 * If we have an update available get the new-features list
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/19/16, 5:30 PM
	 *
	 * @return bool|mixed|string
	 */
	private function latest_features() {
		$cache = new vvv_dash\cache();

		if ( ( $new_features = $cache->get( 'newfeatures-cache', VVV_DASH_THEMES_TTL ) ) == false ) {

			$url          = 'https://raw.githubusercontent.com/' . $this->_repo_user . '/VVV-Dashboard/master/new-features.txt';
			$new_features = get_external_data( $url );
			// Don't save unless we have data
			if ( $new_features && ! strstr( $new_features, 'Error' ) ) {
				$status = $cache->set( 'newfeatures-cache', $new_features );
			}
		}

		return $new_features;
	}
}

// End version.php