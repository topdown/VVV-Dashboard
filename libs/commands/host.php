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

class host {

	public function __construct() {
		$this->_cache   = new \vvv_dash_cache();
		$this->_vvv_dash = new \vvv_dashboard();
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

		if( isset( $_GET['host'] )) {
			$wp_debug_log    = $this->_vvv_dash->get_wp_debug_log( $_GET );
			$debug_log_lines = ( isset( $wp_debug_log['lines'] ) ) ? $wp_debug_log['lines'] : false;
			$debug_log_path  = ( isset( $wp_debug_log['path'] ) ) ? $wp_debug_log['path'] : false;

			if ( $debug_log_path && file_exists( $debug_log_path ) && ! empty( $debug_log_lines ) ) {

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
}
// End host.php