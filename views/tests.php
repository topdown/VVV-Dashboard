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

include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts.php';
include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts/defaults.php';
include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts/standard_wp.php';
include_once VVV_WEB_ROOT . '/default/dashboard/vvv_dash/hosts/wp_starter.php';

$hosts      = new \vvv_dash\hosts();

$defaults   = new \vvv_dash\hosts\defaults();
$defaults->load_hosts();

$standard   = new \vvv_dash\hosts\standard_wp();
$standard->load_hosts();

$wp_starter = new \vvv_dash\hosts\wp_starter();
$wp_starter->load_hosts();

$host_list = \vvv_dash\hosts_container::get_host_list();

echo '<pre style="text-align: left;">' . "FILE: " . __FILE__ . "\nLINE: " . __LINE__ . "\n";
print_r( $host_list );
echo '</pre>------------ Debug End ------------';


// End tests.php