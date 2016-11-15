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

	include_once VVV_DASH_ROOT . '/vvv_dash/blueprints.php';

	$object = new \vvv_dash\blueprints\blueprints( $_GET['host'] );
	//$all_blueprints = $object->get_all_blueprints();
	//$blueprints     = $object->get_blueprints();
	//$the_blueprint  = $object->get_blueprint( 'plugin-example' );

	//	echo '<pre style="text-align: left;">' . "FILE: ". __FILE__ . "\nLINE: " . __LINE__ . "\n";
	//	var_dump($the_blueprint);
	//	echo '</pre>------------ Debug End ------------';

	$new_plugin = new \vvv_dash\blueprints\plugin( 'plugin-example', $_GET['host'] );
	//$info = $new_plugin->get_blueprint( 'plugin-example' );

	//	echo '<pre style="text-align: left;">' . "FILE: ". __FILE__ . "\nLINE: " . __LINE__ . "\n";
	//	var_dump($info);
	//	echo '</pre>------------ Debug End ------------';

	echo '<h3>Create a plugin from a blueprint.</h3>';


	$close = '<a class="close" href="./">Close</a>';

	// Plugins List -------------------------------------------------------------
	?><h4>The plugin list for
	<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php
}
// End plugins.php