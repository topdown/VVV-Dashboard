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

//include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts.php';
//include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts/defaults.php';
//include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts/standard_wp.php';
//include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts/wp_starter.php';
//
//$hosts      = new \vvv_dash\hosts();
//
//$defaults   = new \vvv_dash\hosts\defaults();
//$defaults->load_hosts();
//
//$standard   = new \vvv_dash\hosts\standard_wp();
//$standard->load_hosts();
//
//$wp_starter = new \vvv_dash\hosts\wp_starter();
//$wp_starter->load_hosts();
//
//$host_info = \vvv_dash\hosts_container::get_host_list();

$cache = new \vvv_dash\cache();

if ( ( $host_info = $cache->get( 'hosts-dev', VVV_DASH_HOSTS_TTL ) ) == false ) {
	$host_object = new \vvv_dash\hosts();

	$standard = new \vvv_dash\hosts\standard_wp();
	$standard->load_hosts();

	$wp_starter = new \vvv_dash\hosts\wp_starter();
	$wp_starter->load_hosts();

	$defaults = new \vvv_dash\hosts\defaults();
	$defaults->load_hosts();

	$host_info = \vvv_dash\hosts_container::get_host_list();

	$status = $cache->set( 'hosts-dev', serialize( $host_info ) );
}

//$file = file_get_contents( VVV_WEB_ROOT . '/default/dashboard/cache/host-sites-dev-1462814099.txt' );
$host_info = unserialize( $host_info );

echo '<pre style="text-align: left;">' . "FILE: " . __FILE__ . "\nLINE: " . __LINE__ . "\n";
print_r( $host_info );
echo '</pre>------------ Debug End ------------';


// End tests.php