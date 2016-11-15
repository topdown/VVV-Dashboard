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
 * host.php
 */

namespace vvv_dash\commands;

use vvv_dash\cache;
use vvv_dash\hosts_container;

class host {

	protected $_cache;


	/**
	 * @property bool|mixed $host_info
	 */
	protected $host_info
		= array(
			'hostname'        => '',
			'domain'          => '',
			'web_root'        => '',
			'host_path'       => '',
			'public_dir'      => '',
			'wp_path'         => '',
			'wp_content_path' => '',
			'composer_path'   => '',
			'wp_config_path'  => '',
			'env_path'        => '',
			'debug_log'       => '',
			'wp_is_installed' => 'false',
			'is_wp_site'      => 'false',
			'wp_version'      => '',
			'config_settings' => '',
		);

	public function __construct( $host = '' ) {

		$this->_cache = new cache();

		// If there is a  $_GET['host']
		if ( empty( $host ) ) {
			$host = ( isset( $_GET['host'] ) ) ? $_GET['host'] : '';
		}

		$this->host_info = hosts_container::get_host( $host );
	}

	public function get_host_info() {
		return $this->host_info;
	}


	/**
	 * Display debug logs at a host level if available
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/6/16, 3:14 PM
	 *
	 */
	public function display_debug_logs() {

		if ( isset( $_GET['host'] ) ) {

			$host            = $_GET['host'];
			$wp_debug_log    = $this->get_wp_debug_log( $_GET );
			$debug_log_lines = ( isset( $wp_debug_log['lines'] ) ) ? $wp_debug_log['lines'] : false;

			if ( $wp_debug_log && ! empty( $debug_log_lines ) ) {

				$close  = '<a class="close" href="./">Close</a>';
				$delete = ' <a class="btn btn-danger btn-xs" href="?host=' . $host . '&debug_log=true&delete_debug_log=true"> Delete Log</a>';

				$deleted = $this->_delete_wp_debug_log( $_GET );

				if ( isset( $_GET['delete_debug_log'] ) && $deleted ) { ?>
					<h4>Debug Log for <span class="red"><?php echo $host; ?></span> <?php echo $close; ?></h4>

					<div class="alert alert-danger alert-dismissible" role="alert">
						<strong>The debug log for this site was deleted.</strong><br />
						<?php echo $this->host_info['debug_log']; ?>
					</div>
					<?php

				} else {

					?><h4>Debug Log for
					<span class="red"><?php echo $host; ?></span> <?php echo $delete . $close; ?></h4><?php

					?>
					<div class="wp-debug-log">
						<?php echo $debug_log_lines; ?>
					</div>
					<?php
				}
			}
		}
	}

	/**
	 * Delete the debug log for the requested site
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/8/16, 1:04 PM
	 *
	 * @param $get array Super global $_GET
	 *
	 * @return bool
	 */
	private function _delete_wp_debug_log( $get ) {

		if ( isset( $get['host'] ) && isset( $get['debug_log'] ) // Check defaults
		     && isset( $this->host_info['debug_log'] )
		     && file_exists( $this->host_info['debug_log'] )  // Check file exists
		     && ( substr( $this->host_info['debug_log'], - 4 ) == '.log' ) // Make sure its the right extention (safety)
		     && isset( $get['delete_debug_log'] ) // The request exists
		) {
			unlink( $this->host_info['debug_log'] );  // Delete

			return true;
		}

		return false;
	}


	/**
	 * Gets the WP debug.log content
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:44 AM
	 *
	 * @param $get
	 *
	 * @return bool|array
	 */
	public function get_wp_debug_log( $get ) {

		if ( isset( $get['host'] ) && isset( $get['debug_log'] ) ) {

			$log       = false;
			$debug_log = array();

			if ( isset( $this->host_info['debug_log'] ) && file_exists( $this->host_info['debug_log'] ) ) {
				$log = get_php_errors( 21, 140, $this->host_info['debug_log'] );
			}

			if ( isset( $log[0] ) && $log[0] ) {
				if ( is_array( $log ) ) {
					$debug_log['lines'] = format_php_errors( $log );
				}

				return $debug_log;
			} else {
				return false;
			}
		}

		return false;
	}

	public function get_sub_sites( $host, $path ) {
		if ( ( $sub_sites = $this->_cache->get( $host . '-subsites', VVV_DASH_HOSTS_TTL ) ) == false ) {

			$sub_sites = shell_exec( 'wp site list --path=' . $path . ' --format=json --debug ' );

			// Don't save unless we have data
			if ( $sub_sites ) {
				$status = $this->_cache->set( $host . '-subsites', $sub_sites );
			}
		}

		return $sub_sites;
	}
}

// End host.php