<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/13/16, 11:05 AM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * blueprints.php
 */

namespace vvv_dash\blueprints;

use vvv_dash\commands\host;

include_once 'blueprints/plugin.php';
include_once 'blueprints/theme.php';

/**
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-16 ValidWebs.com
 *
 * Class blueprints
 * @package        vvv_dash\blueprints
 */
class blueprints extends host {

	/**
	 * @property string $_type
	 */
	protected $_type;

	protected $_blueprints = array();

	private $_blueprint = '';

	private $_tmp = VVV_DASH_ROOT . '/vvv_dash/blueprints/tmp/';

	/**
	 * blueprints constructor.
	 *
	 * @param string $host
	 */
	public function __construct( $host ) {
		parent::__construct( $host );

		$this->_type = $this->_get_type();
	}


	/**
	 * Return all blueprints in the system
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/13/16, 11:36 AM
	 *
	 */
	public function get_all_blueprints() {
		$blueprints = dir_to_array( VVV_DASH_ROOT . '/blueprints', false );

		$containers = array();
		foreach ( $blueprints as $key => $blueprint ) {
			$file                        = str_replace( '/srv/www/default/dashboard/blueprints/', '', $blueprint );
			$name                        = str_replace( '.json', '', $file );
			$containers[ $name ]['file'] = $file;
			$containers[ $name ]['name'] = $name;
			$containers[ $name ]['path'] = $blueprint;

			if ( strpos( $file, 'plugin-' ) !== false ) {
				$containers[ $name ]['type'] = 'plugin';
			}

			if ( strpos( $file, 'theme-' ) !== false ) {
				$containers[ $name ]['type'] = 'theme';
			}

		} // end foreach
		unset( $blueprint );


		return $this->_blueprints = $containers;

	}

	/**
	 * Return an array of allowed blueprints on this page.
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/13/16, 1:28 PM
	 *
	 * @return array
	 */
	public function get_blueprints() {

		$containers = array();

		foreach ( $this->_blueprints as $key => $blueprint ) {
			if ( isset( $blueprint['type'] ) && $blueprint['type'] == $this->_type ) {

				$containers[ $key ] = $blueprint;
			}
		} // end foreach

		return $containers;
	}

	/**
	 * Return a specific blueprint to work with
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/13/16, 1:13 PM
	 *
	 * @param $blueprint
	 */
	public function get_blueprint( $blueprint ) {

		$this->_blueprint = $this->_load_blueprint( $blueprint );

		$status = $this->_parse();

		return $status;
	}

	/**
	 * Load the blueprints info file
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/13/16, 1:32 PM
	 *
	 * @param $blueprint
	 *
	 * @return mixed
	 */
	private function _load_blueprint( $blueprint ) {

		$data = false;

		if ( isset( $this->_blueprints[ $blueprint ] ) ) {
			$file_data = file_get_contents( $this->_blueprints[ $blueprint ]['path'] );
			$tags      = file_get_contents( VVV_DASH_ROOT . '/blueprints/' . $this->_type . 's/' . $blueprint . '/tags.json' );
			$tags      = json_decode( $tags );
			$data      = json_decode( $file_data );
			$data      = (object) array_merge( (array) $this->_blueprints[ $blueprint ], (array) $tags, (array) $data );
		}

		return $data;
	}

	/**
	 *
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/13/16, 11:36 AM
	 *
	 */
	private function _parse() {
		echo '<pre style="text-align: left;">' . "FILE: " . __FILE__ . "\nLINE: " . __LINE__ . "\n";
		var_dump( $this->host_info, $this->_blueprint );
		echo '</pre>------------ Debug End ------------';

		$plugin_path = $this->_tmp . '/' . $this->_blueprint->name;


		if ( ! is_dir( $plugin_path ) ) {
			$status = mkdir( $plugin_path );
		} else {

			// Create files and directories
			foreach ( $this->_blueprint->blueprint->include_files as $file ) {

				if ( ! is_dir( $plugin_path . '/' . dirname( $file ) ) ) {
					mkdir( $plugin_path . '/' . dirname( $file ) );
				}

				$blueprint_file = VVV_DASH_ROOT . '/blueprints/' . $this->_type . 's/' . $this->_blueprint->name . '/' . $file;
				touch( $plugin_path . '/' . $file );

				// @ToDO order of operations Warning: file_get_contents() failed to open stream: No such file or directory

				$content = file_get_contents( $blueprint_file );
				$content = $this->_parse_tags( $content );
				file_put_contents($plugin_path . '/' . $file, $content);
			} // end foreach

			// Create plugin main file and add header
			$this->_create_main_file();
			// Write includes to the main file
			$this->_add_includes();

		}

	}

	private function _add_includes() {
		$data        = array();
		$plugin_path = $this->_tmp . '/' . $this->_blueprint->name;

		foreach ( $this->_blueprint->blueprint->include_files as $file ) {
			$data[] = "include_once '$file';\n";

		}

		if ( ! file_exists( $plugin_path . '/includes.txt' ) ) {
			file_put_contents( $plugin_path . '/' . $this->_blueprint->blueprint->plugin_file, $data, FILE_APPEND );
			file_put_contents( $plugin_path . '/includes.txt', implode( "\n", $this->_blueprint->blueprint->include_files ), FILE_APPEND );
		}
	}

	private function _create_main_file() {

		$plugin_path = $this->_tmp . '/' . $this->_blueprint->name;
		// Add plugin header
		$header_info = (array) $this->_blueprint->blueprint->header;
		$start       = "<?php \n" . PHP_EOL;
		$start .= "/**\n *" . PHP_EOL;
		$end      = ' */' . PHP_EOL;
		$security = "\n// If this file is called directly, abort.\nif ( ! defined( 'WPINC' ) ) {\n\tdie; \n}\n" . PHP_EOL;

		$header_data = array();
		foreach ( $header_info as $key => $line ) {
			$header_data[] = ' * ' . ucwords( str_replace( '_', ' ', $key ) ) . ': ' . $line . PHP_EOL;
		} // end foreach

		$data = $start . implode( '', $header_data ) . $end . $security;

		// Create plugin file
		if ( ! file_exists( $plugin_path . '/' . $this->_blueprint->blueprint->plugin_file ) ) {
			file_put_contents( $plugin_path . '/' . $this->_blueprint->blueprint->plugin_file, $data );
		}
	}

	/**
	 * Parse the user supplied tags in each file
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/13/16, 5:55 PM
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	private function _parse_tags( $content ) {

		$tags = array();

		if ( isset( $this->_blueprint->tags ) ) {

			foreach ( $this->_blueprint->tags as $key => $tag ) {
				$tags[] = (array) $tag;
			} // end foreach
		}
		unset( $key, $tag );


		foreach ( $tags as $i => $a ) {

			foreach ( $a as $key => $tag ) {

				$content = str_replace( '{{' . $key . '}}', "$tag", $content );
			} // end foreach


		} // end foreach
		return $content;
	}

	/**
	 * Are we working in plugins section or themes section
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/13/16, 11:35 AM
	 *
	 * @return string
	 */
	private function _get_type() {

		if ( isset( $_GET['get_plugins'] ) ) {
			$this->_type = 'plugin';
		}

		if ( isset( $_GET['get_themes'] ) ) {
			$this->_type = 'theme';
		}

		return $this->_type;
	}

}

// End blueprints.php