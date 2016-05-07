<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 11:44 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * tests.php
 */

include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/paths.php';
include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts.php';
include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts/standard_wp.php';


//get all files in specified directory
$files     = glob( VVV_WEB_ROOT . "/*" );
$host_info = array();
//print each file name
foreach ( $files as $file ) {
	//check to see if the file is a folder/directory
	if ( is_dir( $file ) ) {
		$hosts    = new \vvv_dash\hosts\standard_wp();
		$hostname = str_replace( '/srv/www/', '', $file );
		$hosts->set_host( $hostname );
		$host_data = $hosts->get_host_info();
		
		if($host_data['is_wp_site'] == 'true') {
			$host_info[] = $host_data;
		}
		
	}
}

echo '<pre style="text-align: left;">' . "FILE: " . __FILE__ . "\nLINE: " . __LINE__ . "\n";
print_r( $host_info );
echo '</pre>------------ Debug End ------------';



// End tests.php