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
	protected $host_info;

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

			$wp_debug_log    = $this->get_wp_debug_log( $_GET );
			$debug_log_lines = ( isset( $wp_debug_log['lines'] ) ) ? $wp_debug_log['lines'] : false;

			if ( $wp_debug_log && ! empty( $debug_log_lines ) ) {

				$close = '<a class="close" href="./">Close</a>';

				?><h4>Debug Log for
				<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

				?>
				<div class="wp-debug-log">
					<?php echo $debug_log_lines; ?>
				</div>
				<?php
			}
		}
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
	 * @return bool
	 */
	public function get_wp_debug_log( $get ) {

		if ( isset( $get['host'] ) && isset( $get['debug_log'] ) ) {

			$log       = false;
			$debug_log = array();

			if ( isset( $this->host_info['debug_log'] ) && file_exists( $this->host_info['debug_log'] ) ) {
				$log = get_php_errors( 21, 140, $this->host_info['debug_log'] );
			}

			if ( is_array( $log ) ) {
				$debug_log['lines'] = format_php_errors( $log );
			}

			return $debug_log;
		}

		return false;
	}
}

// End host.php