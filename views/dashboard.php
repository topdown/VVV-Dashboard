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
			$plugin_commands->display( $plugins );

			// Mostly Migrate stuff, come back to this
			$database_commands->display();

			// Themes table
			$theme_commands->display();

			if ( $debug_log_path && file_exists( $debug_log_path ) && ! empty( $debug_log_lines ) ) {

				if ( isset( $_GET['host'] ) ) {
					?><h4>Debug Log for
					<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

					?>
					<div class="wp-debug-log">
						<?php echo $debug_log_lines; ?>
					</div>
					<?php
				}
			}

			vvv_dash_xdebug_status();


			include_once 'views/partials/hosts-list.php'; ?>
		</div>
	</div>

<?php include_once 'views/partials/php-error-logs.php';

// End dashboard.php