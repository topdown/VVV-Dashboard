<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/13/16, 11:10 AM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * themes.php
 */

if ( isset( $_GET['host'] ) && isset( $_GET['get_themes'] ) ) {

	$close = '<a class="close" href="./">Close</a>';
	?><h4>The theme list for
	<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

	// Create a New Theme Form base on _s
	// @var $_GET['host']
	include_once VVV_DASH_VIEWS . '/forms/new_s_theme.php';
}
// End themes.php