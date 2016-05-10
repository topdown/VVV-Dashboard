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
 * theme.php
 */

namespace vvv_dash\commands;

use \vvv_dash;

class theme extends host {


	//	public function __construct( $host ) {
	//
	//		$this->_cache    = new vvv_dash\cache();
	//		$this->_vvv_dash = new vvv_dash\dashboard();
	//		$this->_host     = new host( $host );
	//	}

	/**
	 * Get the hosts list of themes and save to cache
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    11/19/15, 2:56 PM
	 *
	 * @param        $host
	 * @param string $path
	 *
	 * @return bool|string
	 */
	public function get_themes_data( $host, $path = '' ) {

		if ( ( $themes = $this->_cache->get( $host . '-themes', VVV_DASH_THEMES_TTL ) ) == false ) {

			$themes = shell_exec( 'wp theme list --path=' . $path . ' --format=csv' );

			// Don't save unless we have data
			if ( $themes ) {
				$status = $this->_cache->set( $host . '-themes', $themes );
			}
		}

		return $themes;
	}

	/**
	 * Returns the theme list for the requested host
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
	public function get_themes( $get ) {

		if ( isset( $get['host'] ) && isset( $get['get_themes'] ) ) {

			$themes = $this->get_themes_data( $this->host_info['hostname'], $this->host_info['wp_path'] );

			return $themes;
		} else {
			return false;
		}
	}

	public function display() {

		$themes = $this->get_themes( $_GET );

		if ( ! empty( $themes ) || isset( $_GET['themes'] ) ) {
			if ( isset( $_GET['host'] ) ) {

				$close = '<a class="close" href="./">Close</a>';

				?><h4>The theme list for
				<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

				// Create a New Theme Form base on _s
				// @var $_GET['host']
				include_once VVV_DASH_VIEWS . '/forms/new_s_theme.php';

				if ( isset( $_POST['create_s_theme'] ) ) {

					$themes_array = get_csv_names( $themes );
					$slug         = strtolower( str_replace( ' ', '_', $_POST['theme_slug'] ) );

					// @ToDo allow this --force
					if ( in_array( $slug, $themes_array ) ) {
						echo vvv_dash_error( 'Error: That theme already exists!' );
					} else {
						$cmd       = 'wp scaffold _s ' . $slug . ' --theme_name="' . $_POST['theme_name'] . '" --path=' . $this->host_info['wp_path'] .
						             ' --author="' . VVV_DASH_NEW_THEME_AUTHOR . '" --author_uri="' . VVV_DASH_NEW_THEME_AUTHOR_URI . '" --sassify --activate';
						$new_theme = shell_exec( $cmd );

						if ( $new_theme ) {
							$purge_status = $this->_cache->purge( '-themes' );
							echo vvv_dash_notice( $purge_status . ' files were purged from cache!' );
							$new_theme = str_replace( '. Success:', '. <br />Success:', $new_theme );
							echo vvv_dash_notice( $new_theme );
						} else {
							echo vvv_dash_error( '<strong>Error:</strong> Something went wrong!' );
						}
					}
				}

				if ( isset( $_POST['create_child'] ) && isset( $_POST['child'] ) && ! empty( $_POST['child'] ) ) {

					$themes_array = get_csv_names( $themes );
					$child        = strtolower( str_replace( ' ', '_', $_POST['child'] ) );

					if ( in_array( $child, $themes_array ) ) {
						echo vvv_dash_error( 'Error: That theme already exists!' );
					} else {
						$cmd       = 'wp scaffold child-theme ' . $child . ' --theme_name="' . $_POST['theme_name'] . '" --parent_theme=' . $_POST['item'] . ' --path=' . $this->host_info['wp_path'] . ' --debug';
						$new_theme = shell_exec( $cmd );

						if ( $new_theme ) {
							$purge_status = $this->_cache->purge( '-themes' );
							echo vvv_dash_notice( $purge_status . ' files were purged from cache!' );
							echo vvv_dash_notice( $new_theme );
						} else {
							echo vvv_dash_error( '<strong>Error:</strong> Something went wrong!' );
						}
					}
				}

				$themes = $this->get_themes_data( $this->host_info['hostname'], $this->host_info['wp_path'] );

				echo format_table( $themes, $_GET['host'], 'themes' );
			}
		}
	}

	public function version() {

	}

	public function create() {

	}

	public function install() {

	}

	public function update() {
		$status = false;

		if ( ! empty( $_POST['type'] ) && 'themes' == $_POST['type'] ) {
			$status       = shell_exec( 'wp theme update ' . $_POST['item'] . ' --path=' . $this->host_info['wp_path'] );
			$purge_status = $_POST['item'] . ' was updated!<br />';
			$purge_status .= $this->_cache->purge( '-themes' );
			$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
		}

		return $status;
	}
}
// End theme.php