<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/2/15, 10:33 AM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * vvv-dashboard.php
 */

namespace vvv_dash;

/**
 * Class vvv_dashboard
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 */
class dashboard {

	private $_cache;

	private $_pages = array();
	private $_database_commands;

	public function __construct() {

		$this->_cache = new cache();

		//$this->_set_pages();

		//$this->_theme_commands    = new \vvv_dash\commands\theme();
		//$this->_plugin_commands   = new \vvv_dash\commands\plugin();
		//$this->_database_commands = new \vvv_dash\commands\database();
		//$this->_hosts             = new commands\host();

	}

	/**
	 * Setup the dynamic pages from URI query
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/16/15, 5:44 PM
	 *
	 */
	private function _set_pages() {
		$this->_pages = array(
			'dashboard',
			'plugins',
			'themes',
			'backups',
			'about',
			'commands',
		);
	}

	/**
	 * Check the request and return if available.
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/16/15, 5:45 PM
	 *
	 * @return bool|string
	 */
	public function get_page() {

		if ( isset( $_REQUEST['page'] ) && ! empty( $_REQUEST['page'] ) ) {

			if ( in_array( $_REQUEST['page'], $this->_pages ) ) {
				return $_REQUEST['page'];
			} else {
				return 'dashboard';
			}

		} else {
			return false;
		}

	}


	/**
	 * Gets the WP debug.log content
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:44 AM
	 *
	 * @param $get
	 *
	 * @return bool
	 */
	public function get_wp_debug_log( $get ) {
		if ( isset( $get['host'] ) && isset( $get['debug_log'] ) ) {
			$log  = false;
			$type = check_host_type( $get['host'] );

			if ( isset( $type['key'] ) ) {

				if ( isset( $type['path'] ) ) {
					$debug_log['path'] = VVV_WEB_ROOT . '/' . $type['key'] . '/' . $type['path'] . '/wp-content/debug.log';
				} else {
					$debug_log['path'] = VVV_WEB_ROOT . '/' . $type['key'] . '/wp-content/debug.log';
				}

			} else {
				$host              = strstr( $get['host'], '.', true );
				$debug_log['path'] = VVV_WEB_ROOT . '/' . $host . '/htdocs/wp-content/debug.log';
			}

			if ( isset( $debug_log['path'] ) && file_exists( $debug_log['path'] ) ) {
				$log = get_php_errors( 21, 140, $debug_log['path'] );
			}

			if ( is_array( $log ) ) {
				$debug_log['lines'] = format_php_errors( $log );
			}

			return $debug_log;
		}

		return false;
	}

	/**
	 * Process $_POST supper globals used in the dashboard
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/8/15, 4:01 PM
	 *
	 * @return bool|string
	 */
	public function process_post() {

		$status = false;

		if ( isset( $_POST ) ) {

			//			if ( isset( $_POST['install_dev_plugins'] ) && isset( $_POST['host'] ) ) {
			//				$status = $this->_plugin_commands->install_dev_plugins( $_POST );
			//
			//			}


			if ( isset( $_POST['backup'] ) && isset( $_POST['host'] ) ) {
				$this->_database_commands = new commands\database();
				$status = $this->_database_commands->create_db_backup( $_POST['host'] );
			}

			if ( isset( $_POST['roll_back'] ) && $_POST['roll_back'] == 'Roll Back' ) {
				$this->_database_commands = new commands\database();
				$status = $this->_database_commands->db_roll_back( $_POST['host'], $_POST['file_path'] );

				if ( $status ) {
					$status = vvv_dash_notice( $status );
				}
			}

			if ( isset( $_POST['purge_hosts'] ) ) {
				$purge_status = $this->_cache->purge( 'host-sites' );
				$status       = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
			}

			if ( isset( $_POST['purge_themes'] ) ) {
				$purge_status = $this->_cache->purge( '-themes' );
				$status       = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
			}

			if ( isset( $_POST['purge_plugins'] ) ) {
				$purge_status = $this->_cache->purge( '-plugins' );
				$status       = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
			}

			if ( isset( $_POST['update_item'] ) && isset( $_POST['host'] ) ) {

				$type = check_host_type( $_POST['host'] );

				if ( isset( $type['key'] ) ) {

					if ( isset( $type['path'] ) ) {

						if ( ! empty( $_POST['type'] ) && 'plugins' == $_POST['type'] ) {
							$update_status = shell_exec( 'wp plugin update ' . $_POST['item'] . ' --path=' . VVV_WEB_ROOT . '/' . $type['key'] . $type['path'] );
							$purge_status  = $_POST['item'] . ' was updated!<br />';
							$purge_status .= $this->_cache->purge( '-plugins' );
							$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
						}

						if ( ! empty( $_POST['type'] ) && 'themes' == $_POST['type'] ) {
							$status       = shell_exec( 'wp theme update ' . $_POST['item'] . ' --path=' . VVV_WEB_ROOT . '/' . $type['key'] . $type['path'] );
							$purge_status = $_POST['item'] . ' was updated!<br />';
							$purge_status .= $this->_cache->purge( '-themes' );
							$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
						}

					} else {

						if ( ! empty( $_POST['type'] ) && 'plugins' == $_POST['type'] ) {
							$update_status = shell_exec( 'wp plugin update ' . $_POST['item'] . ' --path=' . VVV_WEB_ROOT . '/' . $type['key'] . '/' );
							$purge_status  = $_POST['item'] . ' was updated!<br />';
							$purge_status .= $this->_cache->purge( '-plugins' );
							$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
						}

						if ( ! empty( $_POST['type'] ) && 'themes' == $_POST['type'] ) {
							$update_status = shell_exec( 'wp theme update ' . $_POST['item'] . ' --path=' . VVV_WEB_ROOT . '/' . $type['key'] . '/' );
							$purge_status  = $_POST['item'] . ' was updated!<br />';
							$purge_status .= $this->_cache->purge( '-themes' );
							$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
						}
					}

				} else {
					$host_info = $this->_hosts->set_host_info( $_POST['host'] );
					$is_env    = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;
					$host      = $host_info['host'];

					// WP Starter
					if ( $is_env ) {
						$host_path = VVV_WEB_ROOT . '/' . $host . '/public/wp';
					} else {
						// Normal WP
						$host_path = VVV_WEB_ROOT . '/' . $host . '/' . $host_info['path'];
					}

					if ( ! empty( $_POST['type'] ) && 'plugins' == $_POST['type'] ) {
						$update_status = shell_exec( 'wp plugin update ' . $_POST['item'] . ' --path=' . $host_path );
						$purge_status  = $_POST['item'] . ' was updated!<br />';
						$purge_status .= $this->_cache->purge( '-plugins' );
						$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
					}

					if ( ! empty( $_POST['type'] ) && 'themes' == $_POST['type'] ) {
						$status       = shell_exec( 'wp theme update ' . $_POST['item'] . ' --path=' . $host_path );
						$purge_status = $_POST['item'] . ' was updated!<br />';
						$purge_status .= $this->_cache->purge( '-themes' );
						$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
					}
				}
			}
		}

		return $status;
	}

	public function __destruct() {
		// TODO: Implement __destruct() method.
	}

	/**
	 *
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/8/15, 4:00 PM
	 *
	 * @param $vvv_dash
	 */
	public function process_get( $vvv_dash ) {

	}


}
// End vvv-dashboard.php