<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 11:33 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * host_interface.php
 */

namespace vvv_dash;

interface host_interface {

	public function set_host($host);

	//public function get_domain();

	public function get_path();

	public function get_wp();

	public function get_config();

	public function get_database();


}

// End host_interface.php