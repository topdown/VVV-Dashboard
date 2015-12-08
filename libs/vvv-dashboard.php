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

/**
 * Class vvv_dashboard
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 */
class vvv_dashboard {

	private $_cache;

	public function __construct() {
		$this->_cache = new vvv_dash_cache();
	}

	/**
	 * Returns the host data
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:46 AM
	 *
	 * @param $path
	 *
	 * @return array|bool|string
	 */
	public function get_hosts( $path ) {
		if ( ( $hosts = $this->_cache->get( 'host-sites', VVV_DASH_HOSTS_TTL ) ) == false ) {

			$hosts  = $this->get_hosts_data( $path );
			$status = $this->_cache->set( 'host-sites', serialize( $hosts ) );
		}

		return $hosts;
	}

	/**
	 * Returns the host path
	 *
	 * @ToDO           needs to be updated with the path methods
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:46 AM
	 *
	 * @param $host
	 *
	 * @return string
	 */
	public function get_host_path( $host ) {

		$host_info = $this->set_host_info( $host );
		$is_env    = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;

		// WP Starter
		if ( $is_env ) {
			$host_path = '/public/wp';
		} else {
			// Normal WP
			$host_path = $host_info['path'];
		}

		return $host_path;
	}

	/**
	 * Create an array of the hosts from all of the VVV host files
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2014 ValidWebs.com
	 *
	 * Created:    5/23/14, 12:57 PM
	 *
	 * @param $path
	 *
	 * @return array
	 */
	public function get_hosts_data( $path ) {

		$array = array();
		$debug = array();
		$hosts = array();
		$wp    = array();
		$depth = VVV_DASH_SCAN_DEPTH;
		$site  = new RecursiveDirectoryIterator( $path, RecursiveDirectoryIterator::SKIP_DOTS );
		$files = new RecursiveIteratorIterator( $site );
		if ( ! is_object( $files ) ) {
			return null;
		}
		$files->setMaxDepth( $depth );

		// Loop through the file list and find what we want
		foreach ( $files as $name => $object ) {

			if ( strstr( $name, 'vvv-hosts' ) && ! is_dir( 'vvv-hosts' ) ) {

				$lines = file( $name );
				$name  = str_replace( array( '../../', '/vvv-hosts' ), array(), $name );

				// read through the lines in our host files
				foreach ( $lines as $num => $line ) {

					// skip comment lines
					if ( ! strstr( $line, '#' ) && 'vvv.dev' != trim( $line ) ) {
						if ( 'vvv-hosts' == $name ) {
							switch ( trim( $line ) ) {
								case 'local.wordpress.dev' :
									$hosts['wordpress-default'] = array( 'host' => trim( $line ) );
									break;
								case 'local.wordpress-trunk.dev' :
									$hosts['wordpress-trunk'] = array( 'host' => trim( $line ) );
									break;
								case 'src.wordpress-develop.dev' :
									$hosts['wordpress-develop/src'] = array( 'host' => trim( $line ) );
									break;
								case 'build.wordpress-develop.dev' :
									$hosts['wordpress-develop/build'] = array( 'host' => trim( $line ) );
									break;
							}
						}
						if ( 'vvv-hosts' != $name ) {
							$hosts[ $name ] = array( 'host' => trim( $line ) );
						}
					}
				}
			}

			if ( strstr( $name, 'wp-config.php' ) ) {

				$config_lines = file( $name );
				$name         = str_replace( array( '../../', '/wp-config.php', '/htdocs' ), array(), $name );

				// read through the lines in our host files
				foreach ( $config_lines as $num => $line ) {

					// skip comment lines
					if ( strstr( $line, "define('WP_DEBUG', true);" )
					     || strstr( $line, 'define("WP_DEBUG", true);' )
					     || strstr( $line, 'define( "WP_DEBUG", true );' )
					     || strstr( $line, "define( 'WP_DEBUG', true );" )
					) {
						$debug[ $name ] = array(
							'path'  => $name,
							'debug' => 'true',
						);
					}
				}

				$wp[ $name ] = 'true';
			}
		}

		foreach ( $hosts as $key => $val ) {

			if ( array_key_exists( $key, $debug ) ) {
				if ( array_key_exists( $key, $wp ) ) {
					$array[ $key ] = $val + array( 'debug' => 'true', 'is_wp' => 'true' );
				} else {
					$array[ $key ] = $val + array( 'debug' => 'true', 'is_wp' => 'false' );
				}
			} else {
				if ( array_key_exists( $key, $wp ) ) {
					$array[ $key ] = $val + array( 'debug' => 'false', 'is_wp' => 'true' );
				} else {
					$array[ $key ] = $val + array( 'debug' => 'false', 'is_wp' => 'false' );
				}
			}

			$host_info = $this->set_host_info( $val["host"] );
			$is_env    = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;

			// wp core version --path=<path>
			if ( $is_env ) {
				$host_path = '/public/wp';
			} else {
				// Normal WP
				$host_path = $host_info['path'];
			}

			$wp_version               = shell_exec( 'wp core version --path=' . VVV_WEB_ROOT . '/' . $host_info['host'] . $host_path );
			$array[ $key ]['version'] = $wp_version;

			// Causes load issues do to each API call SO this can not be in a loop
			// @ToDo find a better way
			//$update_check             = shell_exec( 'wp core check-update --path=' . VVV_WEB_ROOT . '/' . $host_info['host'] . $host_path );
			//$array[ $key ]['update']  = $update_check;
		}

		$array['site_count'] = count( $hosts );

		return $array;
	}

	/**
	 * Sets an array containing the needed host info
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:45 AM
	 *
	 * @param $host
	 *
	 * @return array
	 */
	public function set_host_info( $host ) {

		$host_info = array();
		$hosts     = new vvv_dash_hosts();

		// Are these default hosts
		$type = $hosts->check_host_type( $host );

		if ( isset( $type['key'] ) ) {

			if ( isset( $type['path'] ) ) {
				$host_info['host']    = $type['key'];
				$host_info['path']    = $type['path'];
				$host_info['content'] = '/wp-content';
			} else {
				$host_info['host']    = $type['key'];
				$host_info['path']    = '/';
				$host_info['content'] = '/wp-content';
			}

		} else {
			$host_info = $hosts->get_paths( $host );
		}

		$env_file              = $hosts->get_env_file( $host_info );
		$host_info['is_env']   = $hosts->is_env_site( $host_info );
		$host_info['env_path'] = ( isset( $env_file['env_path'] ) ) ? $env_file['env_path'] : '';

		return $host_info;
	}


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

			$themes = shell_exec( 'wp theme list --path=' . VVV_WEB_ROOT . '/' . $host . $path . ' --format=csv' );

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
		if ( isset( $get['host'] ) && isset( $get['themes'] ) ) {
			$host_path = $this->get_host_path( $get['host'] );
			$host_info = $this->set_host_info( $get['host'] );
			$themes    = $this->get_themes_data( $host_info['host'], $host_path );

			return $themes;
		} else {
			return false;
		}
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
		if ( isset( $get['host'] ) && isset( $get['plugins'] ) ) {
			$host_path = $this->get_host_path( $get['host'] );
			$host_info = $this->set_host_info( $get['host'] );
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


	public function install_dev_plugins( $post ) {
		$path      = $this->get_host_path( $post['host'] );
		$host_info = $this->set_host_info( $post['host'] );
		$path      = VVV_WEB_ROOT . '/' . $host_info['host'] . $path;
		$install   = array();

		$dev_plugins = array(
			'debug-bar',
			'plugin-profiler',
			'plugin-profiler-mu',
			'https://github.com/Rarst/laps/releases/download/1.3.2/laps-1.3.2.zip',
		);

		foreach ( $dev_plugins as $key => $plugin ) {
			$install[] = shell_exec( 'wp plugin install ' . $plugin . ' --activate --path=' . $path . ' --debug' );
		} // end foreach

		return implode( '<br /><br />', $install );
	}

	/**
	 * Creates a database dump
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:43 AM
	 *
	 * @param $host
	 *
	 * @return bool|string
	 */
	public function create_db_backup( $host ) {
		$backup_status = false;
		$host_info     = $this->set_host_info( $host );
		$is_env        = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;

		// Backups for WP Starter
		if ( $is_env ) {
			$dash_hosts        = new vvv_dash_hosts();
			$env_configs       = $dash_hosts->get_wp_starter_configs( $host_info );
			$configs           = ( isset( $env_configs[ $host_info['host'] ] ) ) ? $env_configs[ $host_info['host'] ] : false;
			$db['db_name']     = $configs['DB_NAME'];
			$db['db_user']     = $configs['DB_USER'];
			$db['db_password'] = $configs['DB_PASSWORD'];
			$backup_status     = vvv_dash_wp_starter_backup( $host_info, $db );

		} else {
			// All other backups
			$backup_status = vvv_dash_wp_backup( $host );
		}


		return $backup_status;
	}

	/**
	 * Roll back the database with any saved backups in the system
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:43 AM
	 *
	 * @param $host
	 * @param $file
	 *
	 * @return string
	 */
	public function db_roll_back( $host, $file ) {

		$host_info = $this->set_host_info( $host );
		$is_env    = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;

		// Backups for WP Starter
		if ( $is_env ) {
			// @ToDo fix this path issue
			$path   = VVV_WEB_ROOT . '/' . $host_info['host'] . '/' . $host_info['path'] . '/wp/';
			$status = shell_exec( 'wp db import --path=' . $path . ' ' . urldecode( $file ) );

		} else {

			$path   = VVV_WEB_ROOT . '/' . $host_info['host'] . '/htdocs';
			$status = shell_exec( 'wp db import --path=' . $path . ' ' . urldecode( $file ) );

		}


		return $status;
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


	public function process_post() {

		$status       = false;
		
		if ( isset( $_POST ) ) {

			if ( isset( $_POST['install_dev_plugins'] ) && isset( $_POST['host'] ) ) {
				$status = $this->install_dev_plugins( $_POST );

			}


			if ( isset( $_POST['backup'] ) && isset( $_POST['host'] ) ) {
				$status = $this->create_db_backup( $_POST['host'] );
			}

			if ( isset( $_POST['roll_back'] ) && $_POST['roll_back'] == 'Roll Back' ) {
				$status = $this->db_roll_back( $_POST['host'], $_POST['file_path'] );

				if ( $status ) {
					$status = vvv_dash_notice( $status );
				}
			}

			if ( isset( $_POST['purge_hosts'] ) ) {
				$purge_status = $this->_cache->purge( 'host-sites' );
				$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
			}

			if ( isset( $_POST['purge_themes'] ) ) {
				$purge_status = $this->_cache->purge( '-themes' );
				$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
			}

			if ( isset( $_POST['purge_plugins'] ) ) {
				$purge_status = $this->_cache->purge( '-plugins' );
				$status = vvv_dash_notice( $purge_status . ' files were purged from cache!' );
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
					$host_info = $this->set_host_info( $_POST['host'] );
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


}
// End vvv-dashboard.php