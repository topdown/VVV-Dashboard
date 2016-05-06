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

class plugin {

	public function __construct() {
		$this->_cache   = new \vvv_dash_cache();
		$this->vvv_dash = new \vvv_dashboard();
	}

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
			$host_path = $this->vvv_dash->get_host_path( $get['host'] );
			$host_info = $this->vvv_dash->set_host_info( $get['host'] );
			$plugins   = $this->get_plugins_data( $host_info['host'], $host_path );

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
	public function get_plugins_data( $host, $path = '' ) {

		if ( ( $plugins = $this->_cache->get( $host . '-plugins', VVV_DASH_PLUGINS_TTL ) ) == false ) {

			$plugins = shell_exec( 'wp plugin list --path=' . VVV_WEB_ROOT . '/' . $host . $path . ' --format=csv --debug ' );

			// Don't save unless we have data
			if ( $plugins ) {
				$status = $this->_cache->set( $host . '-plugins', $plugins );
			}
		}

		return $plugins;
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
	public function create( $post ) {

		$path      = $this->vvv_dash->get_host_path( $post['host'] );
		$host_info = $this->vvv_dash->set_host_info( $post['host'] );
		$path      = VVV_WEB_ROOT . '/' . $host_info['host'] . $path;
		//		echo '<pre style="text-align: left;">' . "FILE: ". __FILE__ . "\nLINE: " . __LINE__ . "\n";
		//		var_dump($host_info, $path, $post);
		//		echo '</pre>------------ Debug End ------------';

		$install = array();

		// wp scaffold plugin my_test_plugin --activate
		if ( isset( $post['plugin_slug'] ) && ! empty( $post['plugin_slug'] ) ) {

			$author     = ( defined( 'VVV_DASH_NEW_PLUGIN_AUTHOR' ) ) ? '--plugin_author=\'' . VVV_DASH_NEW_PLUGIN_AUTHOR . '\'' : '';
			$author_uri = ( defined( 'VVV_DASH_NEW_PLUGIN_AUTHOR_URI' ) ) ? '--plugin_author_uri=\'' . VVV_DASH_NEW_PLUGIN_AUTHOR_URI . '\'' : '';
			$skip_tests = ( isset( $post['skip_tests'] ) ) ? '--skip-tests' : '';

			$status    = shell_exec( 'wp scaffold  plugin ' . $post['plugin_slug'] . ' --activate ' . $author . ' ' . $author_uri . ' ' . $skip_tests . ' --path=' . $path . ' --debug' );
			$install[] = str_replace( "\n", '<br />', $status );

		} else {
			// We can do anything with this without plugin info
			return false;
		}

		// wp scaffold post-type my_post_type --theme=another_s --plugin=my_test_plugin
		if ( isset( $post['post_types'] ) && isset( $post['plugin_slug'] ) ) {

			foreach ( $post['post_types'] as $pt_key => $pt_slug ) {
				foreach ( $pt_slug as $post_type ) {
					if ( ! empty( $post_type ) ) {
						$install[] = shell_exec( 'wp scaffold  post-type ' . $post_type . ' --plugin=' . $pt_key . ' --path=' . $path . ' --debug' );
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
						$install[] = shell_exec( 'wp scaffold  taxonomy ' . $taxonomy . ' --post_types=' . $t_key . ' --plugin=' . $post['plugin_slug'] . ' --path=' . $path . ' --debug' );
					}
				} // end foreach
				unset( $taxonomy );
			} // end foreach

		}

		if ( sizeof( $install ) ) {

			$install[] = shell_exec( 'wp rewrite flush  --path=' . $path );
			$install[] = '<br />NOTE: You will still need to add includes to your plugin for the post types and taxonomies.';

			return implode( '<br />', $install );
		} else {
			return false;
		}
	}


	public function install() {

	}

	public function update() {

	}
}

// End plugin.php