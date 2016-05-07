<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 11:34 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * hosts.php
 */

namespace vvv_dash;

include_once 'host_interface.php';

class hosts implements host_interface {

	protected $hostname;
	protected $domain;
	protected $web_root        = VVV_WEB_ROOT;
	protected $host_path;
	protected $public_dir;
	protected $wp_path;
	protected $wp_content_path;
	protected $version;
	protected $composer_path   = '';
	protected $wp_config_path  = '';
	protected $env_path        = '';
	protected $wp_is_installed = 'false';
	protected $is_wp_site      = 'false';
	protected $debug_log       = '';
	protected $config_settings = '';

	public function __construct() {
		return $this;
	}

	public function set_host( $host ) {
		$this->hostname = $host;
		$this->set_host_data();
	}

	public function get_host_info() {
		$data = array(
			'hostname'        => $this->hostname,
			'domain'          => $this->domain,
			'web_root'        => $this->web_root,
			'host_path'       => $this->host_path,
			'public_dir'      => $this->public_dir,
			'wp_path'         => $this->wp_path,
			'wp_content_path' => $this->wp_content_path,
			'composer_path'   => $this->composer_path,
			'wp_config_path'  => $this->wp_config_path,
			'env_path'        => $this->env_path,
			'debug_log'       => $this->debug_log,
			'wp_is_installed' => $this->wp_is_installed,
			'is_wp_site'      => $this->is_wp_site,
			'wp_version'      => $this->version,
			'config_settings' => $this->config_settings,
		);

		return $data;
	}

	private function set_host_data() {

		$this->set_path();
		$this->set_domain();
		$this->set_public_dir();
		$this->get_composer_file();
		// $wp_content_path still empty so try custom paths
		$this->set_custom_wp_content();
		// $wp_path still empty so try custom paths
		$this->set_custom_wp_path();
		$this->wp_is_installed();
		$this->is_wp_site();
		$this->get_version();
		$this->get_debug_log_path();
		$this->get_wp_config_path();
		$this->get_env_path();
		$this->get_config_settings();

		//		$wp_version               = shell_exec( 'wp core version --path=' . $this->wp_path );
		//		echo '<pre style="text-align: left;">' . "FILE: ". __FILE__ . "\nLINE: " . __LINE__ . "\n";
		//		var_dump($wp_version);
		//		echo '</pre>------------ Debug End ------------';
		//


		return $this;
	}

	protected function is_wp_site() {
		if ( file_exists( $this->host_path . '/vvv-hosts' ) ) {
			$this->is_wp_site = 'true';
		}
	}

	protected function get_version() {
		if ( file_exists( $this->wp_path . '/wp-includes/version.php' ) ) {

			$version_file = $this->wp_path . '/wp-includes/version.php';
			$file_lines   = file( $version_file );
			$lines        = array_splice( $file_lines, 0, 15 );
			$version      = 'false';

			foreach ( $lines as $num => $line ) {
				if ( strstr( $line, '$wp_version =' ) ) {
					$version = str_replace( array( "'", ";" ), '', trim( explode( ' = ', $line )[1] ) );
				}
			}
			$this->version = $version;
		}
	}

	protected function get_debug_log_path() {
		if ( file_exists( $this->wp_content_path . '/debug.log' ) ) {

			$this->debug_log = $this->wp_content_path . '/debug.log';
		}
	}

	protected function get_wp_config_path() {
		if ( empty( $this->composer_path ) ) {
			// get wp-config
			$config_file = $this->wp_path . '/wp-config.php';
			if ( file_exists( $config_file ) ) {
				$this->wp_config_path = $config_file;
			}
		}
	}

	protected function get_env_path() {
		if ( ! empty( $this->composer_path ) ) {
			// get .env
			$private_file = $this->host_path . '/.env';
			$public_file  = $this->host_path . '/' . $this->public_dir . '/.env';

			if ( file_exists( $public_file ) ) {
				$this->env_path = $public_file;
				$config_file    = $this->host_path . '/' . $this->public_dir . '/wp-config.php';
				if ( file_exists( $config_file ) ) {
					$this->wp_config_path = $config_file;
				}

			} elseif ( file_exists( $private_file ) ) {
				$this->env_path = $private_file;
				$config_file    = $this->host_path . '/' . $this->public_dir . '/wp-config.php';
				if ( file_exists( $config_file ) ) {
					$this->wp_config_path = $config_file;
				}

			}
		}
	}


	protected function get_config_settings() {
		if ( empty( $this->env_path ) ) {
			// get wp-config
			if ( file_exists( $this->wp_config_path ) ) {

				$this->config_settings = $this->get_wp_configs();

			}
		} elseif ( $this->env_path ) {
			if ( file_exists( $this->env_path ) ) {

				$this->config_settings = $this->get_wp_starter_configs();

			}
		}
	}

	protected function get_wp_configs() {
		$config_array = array();
		$lines        = file( $this->wp_config_path );
		//$name         = str_replace( array( '../../', '/wp-config.php', '/htdocs' ), array(), $name );


		// read through the lines in our host files
		foreach ( $lines as $num => $line ) {

			if ( strstr( $line, 'DB_NAME' ) ) {
				$config_array['DB_NAME'] = $line;
			}

			if ( strstr( $line, 'DB_USER' ) ) {
				$config_array['DB_USER'] = $line;
			}

			if ( strstr( $line, 'DB_PASSWORD' ) ) {
				$config_array['DB_PASSWORD'] = $line;
			}

			if ( strstr( $line, 'WP_DEBUG"' ) || strstr( $line, "WP_DEBUG'" ) ) {
				$config_array['WP_DEBUG'] = $line;
			}

			if ( strstr( $line, 'WP_DEBUG_LOG' ) ) {
				$config_array['WP_DEBUG_LOG'] = $line;
			}

			if ( strstr( $line, 'SCRIPT_DEBUG' ) ) {
				$config_array['SCRIPT_DEBUG'] = $line;
			}

			if ( strstr( $line, 'WP_DEBUG_DISPLAY' ) ) {
				$config_array['WP_DEBUG_DISPLAY'] = $line;
			}

			if ( strstr( $line, 'SAVEQUERIES' ) ) {
				$config_array['SAVEQUERIES'] = $line;
			}

		} // end foreach

		$settings = array();

		foreach ( $config_array as $key => $config ) {

			if ( strstr( $config, '//' ) ) {
				$settings[ $key ] = 'false';
			} else {
				$search = array(
					' ',
					'define',
					'(',
					')',
					',',
					"'",
					$key,
					';'
				);
				//$settings[ $key ] = $config;
				$settings[ $key ] = trim( str_replace( $search, '', $config ) );
			}


		} // end foreach
		unset( $config );

		return $settings;
	}

	protected function get_wp_starter_configs() {

		$config_array = array();


		//$env_file = VVV_WEB_ROOT . '/' . $host_info['host'] . '/.env';

		$env_lines = file( $this->env_path );
		$lines     = array_splice( $env_lines, 0, 15 );
		$env_array = array();


		foreach ( $lines as $num => $line ) {
			if ( strstr( $line, "WORDPRESS_ENV=" )
			     || strstr( $line, 'DB_NAME=' )
			     || strstr( $line, 'DB_USER=' )
			     || strstr( $line, "DB_PASSWORD=" )
			) {
				switch ( $line ) {
					case strstr( $line, "WORDPRESS_ENV=" ) :
						$env_array['WORDPRESS_ENV'] = trim( explode( '=', $line )[1] );
						break;

					case strstr( $line, 'DB_NAME=' ) :
						$env_array['DB_NAME'] = trim( explode( '=', $line )[1] );
						break;

					case strstr( $line, 'DB_USER=' ) :
						$env_array['DB_USER'] = trim( explode( '=', $line )[1] );
						break;

					case strstr( $line, "DB_PASSWORD=" ) :
						$env_array['DB_PASSWORD'] = trim( explode( '=', $line )[1] );
						break;
				}
			}
		} // end foreach

		$config_array = $env_array;
		$vars         = array();
		$file         = $this->wp_config_path;
		$config_lines = file( $file );
		$array1       = array_chunk( $config_lines, 70 )[1];
		$array2       = array_chunk( $array1, 27 )[0];
		$env_sec      = implode( PHP_EOL, $array2 );
		$env_array    = explode( 'break;', $env_sec );
		$env          = trim( $config_array['WORDPRESS_ENV'] );

		foreach ( $env_array as $key => $chunk ) {
			$chunk = str_replace(
				array(
					' */',
					"\$environment = getenv('WORDPRESS_ENV');",
					'switch ($environment) {',
					"\n\n\n",
					"\t",
					"  ",
					"defined('WP_DEBUG')",
					"defined('WP_DEBUG_DISPLAY')",
					"defined('WP_DEBUG_LOG')",
					"defined('SCRIPT_DEBUG')",
					"defined('SAVEQUERIES')",
					"or ",
					"default:",
				),
				'', $chunk );

			if ( strstr( $chunk, "case '$env':" ) ) {

				$str = str_replace( array(
					"case '$env':",
					' ',
					"define('",
					");",
					"'"
				), '', $chunk );

				$test = array_filter( explode( "\n", $str ) );
				foreach ( $test as $cl ) {
					$k = strstr( $cl, ',', true );
					$v = strstr( $cl, ',' );

					$vars[ $k ] = str_replace( ',', '', $v );
				} // end foreach

				$config_array = $vars;
			}

		} // end foreach

		return $config_array;
	}

	/**
	 * Check to see if WP is actually installed
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/7/16, 3:15 AM
	 *
	 * @return string
	 */
	protected function wp_is_installed() {
		if ( file_exists( $this->wp_path . '/wp-config.php' ) ) {
			$this->wp_is_installed = 'true';
		}

		// wpstarter
		if ( file_exists( $this->host_path . '/' . $this->public_dir . '/wp-config.php' ) ) {
			$this->wp_is_installed = 'true';
		}

		return $this->wp_is_installed;
	}

	protected function set_public_dir() {
		if ( file_exists( $this->host_path . '/wp-cli.yml' ) ) {
			$path             = file_get_contents( $this->host_path . '/wp-cli.yml' );
			$this->public_dir = str_replace( 'path: ', '', $path );
		}
	}

	protected function set_path() {
		return $this->host_path = $this->web_root . '/' . $this->hostname;
	}

	protected function set_domain() {

		if ( file_exists( $this->host_path . '/vvv-hosts' ) ) {
			$domain       = file_get_contents( $this->host_path . '/vvv-hosts' );
			$this->domain = trim( $domain );
		}
	}

	/**
	 * If these are env hosts they have composer installs
	 * So we can use the info from composer.json
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/7/16, 3:15 AM
	 *
	 */
	protected function get_composer_file() {

		// Check common path for a wp composer install
		if ( file_exists( $this->host_path . '/composer.json' ) ) {

			$json = json_decode( file_get_contents( $this->host_path . '/composer.json' ), true );

			if ( isset( $json['extra']['wordpress-install-dir'] ) ) {
				if ( is_dir( $this->host_path . '/' . $json['extra']['wordpress-install-dir'] ) ) {

					$this->wp_path       = $this->host_path . '/' . $json['extra']['wordpress-install-dir'];
					$this->composer_path = $this->host_path . '/composer.json';

				} elseif ( is_dir( $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'] ) ) {

					$this->wp_path       = $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'];
					$this->composer_path = $this->host_path . '/composer.json';
				}
			}

			if ( isset( $json['extra']['wordpress-content-dir'] ) ) {
				// Breaks path, don't use here
				//$this->wp_content_path =  $json['extra']['wordpress-content-dir'];
			}

		}

		// if for some screwy reason they have it in the public path
		if ( file_exists( $this->host_path . '/' . $this->public_dir . '/composer.json' ) ) {

			$json = json_decode( file_get_contents( $this->host_path . '/' . $this->public_dir . '/composer.json' ), true );

			if ( isset( $json['extra']['wordpress-install-dir'] ) ) {
				//$this->wp_path = $json['extra']['wordpress-install-dir'];

				if ( is_dir( $this->host_path . '/' . $json['extra']['wordpress-install-dir'] ) ) {

					$this->wp_path       = $this->host_path . '/' . $json['extra']['wordpress-install-dir'];
					$this->composer_path = $this->host_path . '/' . $this->public_dir . '/composer.json';

				} elseif ( is_dir( $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'] ) ) {

					$this->wp_path       = $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'];
					$this->composer_path = $this->host_path . '/' . $this->public_dir . '/composer.json';
				}
			}

			if ( isset( $json['extra']['wordpress-content-dir'] ) ) {
				$this->wp_content_path = $json['extra']['wordpress-content-dir'];
				$this->composer_path   = $this->host_path . '/' . $this->public_dir . '/composer.json';
			}
		}
	}

	/**
	 * Last effort check to set paths
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/7/16, 3:10 AM
	 *
	 * @return string
	 */
	protected function set_custom_wp_content() {

		if ( empty( $this->wp_content_path ) ) {

			$check_content_paths = paths::get_wp_content_paths();

			foreach ( $check_content_paths as $key => $path ) {

				foreach ( $path as $dir ) {
					if ( is_dir( $this->host_path . '/' . $this->public_dir . '/' . $dir ) ) {
						$this->wp_content_path = $this->host_path . '/' . $this->public_dir . '/' . $dir;
					}
				} // end foreach
				unset( $dir );
			} // end foreach
			unset( $path );

			return $this->wp_content_path;

		}
	}

	/**
	 * Last effort check to set paths
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    5/7/16, 3:11 AM
	 *
	 */
	protected function set_custom_wp_path() {

		if ( empty( $this->wp_path ) ) {

			$check_scan_paths = paths::get_scan_paths();

			foreach ( $check_scan_paths as $key => $path ) {

				foreach ( $path as $dir ) {
					if ( is_dir( $this->host_path . '/' . $dir ) ) {
						$this->wp_path = $this->host_path . '/' . $dir;
					}
				} // end foreach
				unset( $dir );
			} // end foreach
			unset( $path );

			return $this->wp_path;
		}
	}

}

// End hosts.php