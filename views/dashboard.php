<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/7/15, 3:43 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * dashboard.php
 */

/**
 * This is the base view for the dashboard
 */
?>
	<div class="row">
		<div class="col-sm-12 hosts">
			<?php
			
			// Plugins table
			$plugin_commands->display();

			// Themes table
			$theme_commands->display();

			// Debug logs
			$host_commands->display_debug_logs();

			vvv_dash_xdebug_status();

			// @var $host_info loaded from the index.php
			include_once VVV_DASH_VIEWS . '/partials/hosts-list2.php'; ?>
		</div>
	</div>

<?php include_once VVV_DASH_VIEWS . '/partials/php-error-logs.php';

// End dashboard.php