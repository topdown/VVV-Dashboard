<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/13/16, 10:59 AM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * plugin.php
 */

namespace vvv_dash\blueprints;

/**
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-16 ValidWebs.com
 *
 * Class plugin
 * @package        vvv_dash\blueprints
 */
class plugin extends \blueprints {

	private $_post_types;
	private $_taxonomies;
	//private $_plugin;
	private $_plugin_slug;


	public function __construct( $blueprint, $host ) {

		parent::__construct( $host );

		$this->_blueprint = parent::get_blueprint( $blueprint );

		// @ToDo change this to $_POST
		$this->_plugin_slug = $blueprint;

		$this->_set_blueprint_post_types();
		$this->_set_blueprint_taxonomies();

		//$this->_run_wpcli();

		$status = $this->_process();

		if ( $status ) {

			// Create plugin main file and add header
			$this->_create_main_file();

			// Write includes to the main file
			$this->_add_includes();

			// Move files
			$status = $this->_install();

			if ( $status != null ) {
				echo '<pre style="text-align: left;">' . "FILE: " . __FILE__ . "\nLINE: " . __LINE__ . "\n";
				var_dump( $status );
				echo '</pre>------------ Debug End ------------';
			}

		} else {
			// Errors
		}

		return $status;
	}

	private function _install() {

		$install = array();

		$new_plugin = $this->host_info['wp_content_path'] . '/' . $this->_type . 's/' . $this->_plugin_slug . '/';

		if ( ! dir( $new_plugin ) ) {
			// Move the plugin files to the host
			$move = rename( $this->_tmp . '/' . $this->_plugin_slug . '/', $new_plugin );

			if ( $move ) {

				// Run the required WP CLI commands if needed.
				$install[] = $this->_run_wpcli();

				if ( sizeof( $install ) || $move ) {
					// We moved the plugin, lets activate it
					$install[] = shell_exec( 'wp plugin activate ' . $this->_plugin_slug . '  --path=' . $this->host_info['wp_path'] );

					// Does not seem to work
					// Incase we have post types, flush rewrites
					//$install[] = shell_exec( 'wp rewrite flush  --path=' . $this->host_info['wp_path'] );
				}

				if(sizeof($install)) {
					foreach ( $install as $notice ) {
						echo vvv_dash_notice( $notice );
					} // end foreach
					unset( $notice );
				}

				$purge_status = $this->_cache->purge( $this->host_info['hostname'] . '-plugins' );
				echo vvv_dash_notice( $purge_status . ' files were purged from cache!' );

			}

		} else {
			echo '<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button><strong>ERROR:</strong>
		<p>That plugin already exists in this host and is installed. You must delete it to proceed.</p>
		</div>';
		}
	}

	/**
	 * Run WP CLI commands
	 *
	 * @bug            this causes server error 504
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/15/16, 1:20 PM
	 *
	 * @return array
	 */
	private function _run_wpcli() {
		$install = array();

		if ( sizeof( $this->_post_types ) ) {
			foreach ( $this->_post_types as $post_type ) {
				if ( ! empty( $post_type ) ) {
					$install[] = $this->_create_post_type( $post_type );
				}
			} // end foreach
		}

		if ( sizeof( $this->_taxonomies ) ) {
			foreach ( $this->_taxonomies as $t_key => $tax_slug ) {
				foreach ( $tax_slug as $taxonomy ) {
					if ( ! empty( $taxonomy ) ) {
						$install[] = $this->_create_taxonomy( $t_key, $taxonomy );
					}
				} // end foreach
				unset( $taxonomy );
			} // end foreach
		}

		return $install;
	}

	private function _create_post_type( $post_type ) {

		return shell_exec( 'wp scaffold  post-type ' . $post_type . ' --plugin=' . $this->_plugin_slug . ' --path=' . $this->host_info['wp_path'] . ' --debug' );
	}


	private function _create_taxonomy( $post_type, $taxonomy ) {
		return shell_exec( 'wp scaffold  taxonomy ' . $taxonomy . ' --post_types=' . $post_type . ' --plugin=' . $this->_plugin_slug . ' --path=' . $this->host_info['wp_path'] . ' --debug' );
	}


	private function _add_includes() {
		$data        = array();
		$taxonomies  = array();
		$plugin_path = $this->_tmp . '/' . $this->_blueprint->name;

		foreach ( $this->_post_types as $file ) {
			$data[] = "include_once plugin_dir_path( __FILE__ ) . '/post-types/$file.php';\n";
		}
		unset( $post_type );

		// @ToDo something still not correct here Getting: Skip this file, or replace it with scaffolding?[s/r]
		// Remove possible duplicates
		foreach ( $this->_taxonomies as $post_type ) {
			foreach ( $post_type as $file ) {
				$taxonomies[ $file ] = $file;
			} // end foreach
			unset( $file );
		}

		foreach ( $taxonomies as $file ) {
			$data[] = "include_once plugin_dir_path( __FILE__ ) . '/taxonomies/$file.php';\n";
		}
		unset( $post_type );


		foreach ( $this->_blueprint->blueprint->include_files as $file ) {
			$data[] = "include_once plugin_dir_path( __FILE__ ) . '/$file';\n";
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
	 *
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/13/16, 11:36 AM
	 *
	 */
	private function _process() {

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

				if ( file_exists( $blueprint_file ) ) {
					$content = file_get_contents( $blueprint_file );
					$content = $this->_parse_tags( $content );
					file_put_contents( $plugin_path . '/' . $file, $content );
				} else {
					echo '<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button><strong>ERROR:</strong>
		<p>You have a file listed in your blueprint JSON file that does not exist at:<br />' . $blueprint_file . '</p>
		<p>So we created it. Please re-run this page.</p>
		</div>';
					if ( ! is_dir( dirname( $blueprint_file ) ) ) {
						mkdir( dirname( $blueprint_file ) );
						touch( $blueprint_file );
					} else {
						touch( $blueprint_file );
					}
				}

			} // end foreach

			return true;
		}

		return false;
	}

	/**
	 * Setup the post types array
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    11/15/16, 12:14 PM
	 *
	 * @return mixed
	 */
	private function _set_blueprint_post_types() {
		if ( isset( $this->_blueprint->blueprint->post_types ) ) {

			$this->_post_types = (array) $this->_blueprint->blueprint->post_types;
		}

		return $this->_post_types;
	}

	/**
	 * Return the taxonomies
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-16 ValidWebs.com
	 *
	 * Created:    the_date
	 *
	 * @return mixed
	 */
	private function _set_blueprint_taxonomies() {
		if ( isset( $this->_blueprint->blueprint->taxonomies ) ) {

			foreach ( $this->_blueprint->blueprint->taxonomies as $item ) {
				foreach ( $item as $key => $taxonomy ) {
					$this->_taxonomies[ $key ] = (array) $taxonomy;
				} // end foreach
			} // end foreach
		}

		return $this->_taxonomies;
	}


}

// End plugin.php