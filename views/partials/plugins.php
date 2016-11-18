<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/13/16, 11:09 AM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * plugins.php
 */

if ( isset( $_GET['host'] ) && isset( $_GET['get_plugins'] ) ) {

	$close = '<a class="close" href="./">Close</a>';

	// Plugins List -------------------------------------------------------------
	?><h4>The plugin list for
	<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php
}
// End plugins.php