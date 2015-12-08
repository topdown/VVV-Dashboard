<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/7/15, 3:56 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * php-error-logs.php
 */
?>
	<div class="error_logs"><h3>Last 10 PHP Errors</h3>
		<?php
		$lines = get_php_errors();
		$lines = format_php_errors( $lines );

		echo $lines;
		?>
	</div>
<?php

// End php-error-logs.php