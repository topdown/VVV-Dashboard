<?php

/**
 *
 * PHP version 5
 *
 * Created: 2/19/16, 4:13 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * plugin.php
 */

namespace vvv_dash\commands;

use \vvv_dash;

class plugin extends host {

	//	public function __construct() {
	//		$this->_cache    = new vvv_dash\cache();
	//		//$this->_vvv_dash = new vvv_dash\dashboard();
	//		//$this->_hosts = new host();
	//		//$this->_vvv_dash = new \vvv_dashboard();
	//		$this->favs = new favs();
	//	}

	/**
	 * Returns the plugin list for the requested host
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:44 AM
	 *
	 * @param $get
	 *
	 * @return bool|string
	 */
	public function get_plugins( $get ) {

		if ( isset( $get['host'] ) && isset( $get['get_plugins'] ) ) {
			//$host_path = $this->_hosts->get_host_path( $get['host'] );
			//$host_info = $this->_hosts->set_host_info( $get['host'] );
			$plugins = $this->_get_plugins_data( $this->host_info['hostname'], $this->host_info['wp_path'] );

			return $plugins;
		} else {
			return false;
		}
	}

	/**
	 * Get the hosts list of plugins and save to cache
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    11/19/15, 2:55 PM
	 *
	 * @param        $host
	 * @param string $path
	 *
	 * @return bool|string
	 */
	private function _get_plugins_data( $host, $path = '' ) {

		if ( ( $plugins = $this->_cache->get( $host . '-plugins', VVV_DASH_PLUGINS_TTL ) ) == false ) {

			$plugins = shell_exec( 'wp plugin list --path=' . $path . ' --format=csv --debug ' );

			// Don't save unless we have data
			if ( $plugins ) {
				$status = $this->_cache->set( $host . '-plugins', $plugins );
			}
		}

		return $plugins;
	}

	private function _plugin_list() {
		// Need to re-get the plugin list for this host.
		$plugins = $this->get_plugins( $_GET );

		echo format_table( $plugins, $_GET['host'], 'plugins' );
	}

	/**
	 * Display plugin data/info and forms
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/6/16, 2:29 PM
	 *
	 */
	public function display() {

		if ( isset( $_GET['host'] ) && isset( $_GET['get_plugins'] ) ) {

			$close = '<a class="close" href="./">Close</a>';

			// Install fav plugins -------------------------------------------------------------
			$this->_favorite_plugins();

			// Create New Plugin -------------------------------------------------------------
			$this->_new_plugin();

			include_once VVV_DASH_VIEWS . '/partials/plugins.php';

			$this->_plugin_list();
		}

	}

	public function version() {

	}

	/**
	 * Creates a plugin with included test files
	 * Also options to create post types and taxonomies.
	 *
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/16/15, 3:21 PM
	 *
	 * @param array $post
	 *
	 * @return bool|string
	 */
	private function _create( $post ) {

		$install = array();

		// wp scaffold plugin my_test_plugin --activate
		if ( isset( $post['plugin_slug'] ) && ! empty( $post['plugin_slug'] ) ) {

			$author     = ( defined( 'VVV_DASH_NEW_PLUGIN_AUTHOR' ) ) ? '--plugin_author=\'' . VVV_DASH_NEW_PLUGIN_AUTHOR . '\'' : '';
			$author_uri = ( defined( 'VVV_DASH_NEW_PLUGIN_AUTHOR_URI' ) ) ? '--plugin_author_uri=\'' . VVV_DASH_NEW_PLUGIN_AUTHOR_URI . '\'' : '';
			$skip_tests = ( isset( $post['skip_tests'] ) ) ? '--skip-tests' : '';
			$blueprint  = ( isset( $post['blueprint'] ) & ! empty( $post['blueprint'] ) ) ? $post['blueprint'] : false;
			$status     = shell_exec( 'wp scaffold  plugin ' . $post['plugin_slug'] . ' --activate ' . $author . ' ' . $author_uri . ' ' . $skip_tests . ' --path=' . $this->host_info['wp_path'] . ' --debug' );
			$install[]  = str_replace( "\n", '<br />', $status );

			//			if ( $blueprint ) {
			//				$status    = new vvv_dash\blueprints\plugin( $blueprint, $post['host'] );
			//				$install[] = str_replace( "\n", '<br />', $status );
			//			}
		} else {
			// We can do anything with this without plugin info
			return false;
		}

		// wp scaffold post-type my_post_type --theme=another_s --plugin=my_test_plugin
		if ( isset( $post['post_types'] ) && isset( $post['plugin_slug'] ) ) {

			foreach ( $post['post_types'] as $pt_key => $pt_slug ) {
				foreach ( $pt_slug as $post_type ) {
					if ( ! empty( $post_type ) ) {
						$install[] = shell_exec( 'wp scaffold  post-type ' . $post_type . ' --plugin=' . $pt_key . ' --path=' . $this->host_info['wp_path'] . ' --debug' );
					}
				} // end foreach
				unset( $pt );
			} // end foreach

		}

		// wp scaffold taxonomy venue --post_types=my_post_type --theme=another_s
		if ( isset( $post['taxonomies'] ) ) {

			foreach ( $post['taxonomies'] as $t_key => $tax_slug ) {
				foreach ( $tax_slug as $taxonomy ) {
					if ( ! empty( $taxonomy ) ) {
						$install[] = shell_exec( 'wp scaffold  taxonomy ' . $taxonomy . ' --post_types=' . $t_key . ' --plugin=' . $post['plugin_slug'] . ' --path=' . $this->host_info['wp_path'] . ' --debug' );
					}
				} // end foreach
				unset( $taxonomy );
			} // end foreach

		}

		if ( sizeof( $install ) ) {

			$install[] = shell_exec( 'wp rewrite flush  --path=' . $this->host_info['wp_path'] );
			$install[] = '<br />NOTE: You will still need to add includes to your plugin for the post types and taxonomies.';

			return implode( '<br />', $install );
		} else {
			return false;
		}
	}

	private function _new_plugin() {

		// @var $host
		include_once VVV_DASH_VIEWS . '/forms/create_plugin.php';

		if ( isset( $_POST['create_plugin'] ) ) {

			$create_plugin = $this->_create( $_POST );

			if ( ! empty( $create_plugin ) ) {

				echo vvv_dash_notice( $create_plugin );
				$host_name    = $this->host_info['hostname'];
				$purge_status = $this->_cache->purge( $host_name . '-plugins' );
				echo vvv_dash_notice( $purge_status . ' files were purged from cache!' );
			}
		}
	}

	/**
	 * Install a favorite plugin from the list
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/6/16, 1:43 PM
	 *
	 * @param $post
	 *
	 * @return bool|string
	 */
	private function _install( $post ) {

		if ( isset( $post['install_fav_plugin'] ) ) {

			$favs                  = new favs();
			$plugin_install_status = $favs->install_fav_items( $post, 'plugin' );

			if ( ! empty( $plugin_install_status ) ) {
				$plugin_install_status = str_replace( PHP_EOL, '<br />', $plugin_install_status );
				$return                = vvv_dash_notice( $plugin_install_status );
				$host_name             = str_replace( '.dev', '', $post['host'] );
				$purge_status          = $this->_cache->purge( $host_name . '-plugins' );
				$return .= vvv_dash_notice( $purge_status . ' files were purged from cache!' );

				return $return;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}

	private function _install_favorite( $post ) {

		// Install plugin
		$install_plugin = $this->_install( $post );

		if ( ! empty( $install_plugin ) ) {
			echo $install_plugin;
		}
	}

	private function _favs_checkboxes( $fav_file ) {
		$favs = new favs();

		return $favs->get_fav_list( $fav_file );
	}

	/**
	 *
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/6/16, 2:23 PM
	 *
	 */
	private function _favorite_plugins() {

		$fav_file   = VVV_WEB_ROOT . '/default/dashboard/favorites/plugins.txt';
		$checkboxes = $this->_favs_checkboxes( $fav_file );

		if ( $checkboxes ) {
			// @var $checkboxes
			// @var $host
			include_once VVV_DASH_VIEWS . '/forms/favorite_plugins.php';
		} else {

			echo vvv_dash_error( '<strong>You have no favorite plugins to install.</strong><br />
								Create a file ' . $fav_file . ' with your plugins 1 per line.<br />
								SEE: ' . VVV_WEB_ROOT . '/default/dashboard/favorites/plugins-example.txt', 'no_plugin_fav_list' );
		}

		$this->_install_favorite( $_POST );
	}

	public function update() {

		$status = false;
		if ( ! empty( $_POST['type'] ) && 'plugins' == $_POST['type'] ) {
			$update_status = shell_exec( 'wp plugin update ' . $_POST['item'] . ' --path=' . $this->host_info['wp_path'] );
			$purge_status  = $_POST['item'] . ' was updated!<br />';
			$purge_status .= $this->_cache->purge( '-plugins' );
			$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
		}

		return $status;
	}
}

// End plugin.php