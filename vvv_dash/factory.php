<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/4/15, 1:07 AM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * vvv-dash-commands.php
 */

/**
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Class Automobile
 */
class Automobile {
	private $vehicleMake;
	private $vehicleModel;

	public function __construct( $make, $model ) {
		$this->vehicleMake  = $make;
		$this->vehicleModel = $model;
	}

	public function getMakeAndModel() {
		return $this->vehicleMake . ' ' . $this->vehicleModel;
	}
}

/**
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Class AutomobileFactory
 */
class AutomobileFactory {
	public static function create( $make, $model ) {
		return new Automobile( $make, $model );
	}
}

// have the factory create the Automobile object
$veyron = AutomobileFactory::create( 'Bugatti', 'Veyron' );

print_r( $veyron->getMakeAndModel() ); // outputs "Bugatti Veyron"
// End vvv-dash-commands.php