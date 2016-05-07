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

abstract class hosts implements host_interface {

	protected $hostname;
	protected $domain;
	protected $web_root        = VVV_WEB_ROOT;
	protected $host_path;
	protected $public_dir;
	protected $wp_path;
	protected $wp_content_path;
	protected $wp_is_installed = 'false';
	protected $is_wp_site      = 'false';

	public function __construct() {
		return $this;
	}

	public function set_host( $host ) {
		$this->hostname = $host;
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


		//		$wp_version               = shell_exec( 'wp core version --path=' . $this->wp_path );
		//		echo '<pre style="text-align: left;">' . "FILE: ". __FILE__ . "\nLINE: " . __LINE__ . "\n";
		//		var_dump($wp_version);
		//		echo '</pre>------------ Debug End ------------';


		return $this;
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
			'wp_is_installed' => $this->wp_is_installed,
			'is_wp_site'      => $this->is_wp_site,
		);

		return $data;
	}

	public function is_wp_site() {
		if ( file_exists( $this->host_path . '/vvv-hosts' ) ) {
			$this->is_wp_site = 'true';
		}
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
	public function wp_is_installed() {
		if ( file_exists( $this->wp_path . '/wp-config.php' ) ) {
			$this->wp_is_installed = 'true';
		}

		// wpstarter
		if ( file_exists( $this->host_path . '/' . $this->public_dir . '/wp-config.php' ) ) {
			$this->wp_is_installed = 'true';
		}

		return $this->wp_is_installed;
	}

	public function set_public_dir() {
		if ( file_exists( $this->host_path . '/wp-cli.yml' ) ) {
			$path             = file_get_contents( $this->host_path . '/wp-cli.yml' );
			$this->public_dir = str_replace( 'path: ', '', $path );
		}
	}

	public function set_path() {
		return $this->host_path = $this->web_root . '/' . $this->hostname;
	}

	public function set_domain() {

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
	public function get_composer_file() {

		// Check common path for a wp composer install
		if ( file_exists( $this->host_path . '/composer.json' ) ) {

			$json = json_decode( file_get_contents( $this->host_path . '/composer.json' ), true );

			if ( isset( $json['extra']['wordpress-install-dir'] ) ) {
				if ( is_dir( $this->host_path . '/' . $json['extra']['wordpress-install-dir'] ) ) {
					$this->wp_path = $this->host_path . '/' . $json['extra']['wordpress-install-dir'];
				} elseif ( is_dir( $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'] ) ) {
					$this->wp_path = $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'];
				}
			}

			if ( isset( $json['extra']['wordpress-content-dir'] ) ) {
				$this->wp_content_path = $json['extra']['wordpress-content-dir'];
			}
		}

		// if for some screwy reason they have it in the public path
		if ( file_exists( $this->host_path . '/' . $this->public_dir . '/composer.json' ) ) {

			$json = json_decode( file_get_contents( $this->host_path . '/' . $this->public_dir . '/composer.json' ), true );

			if ( isset( $json['extra']['wordpress-install-dir'] ) ) {
				//$this->wp_path = $json['extra']['wordpress-install-dir'];

				if ( is_dir( $this->host_path . '/' . $json['extra']['wordpress-install-dir'] ) ) {
					$this->wp_path = $this->host_path . '/' . $json['extra']['wordpress-install-dir'];
				} elseif ( is_dir( $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'] ) ) {
					$this->wp_path = $this->host_path . '/' . $this->public_dir . '/' . $json['extra']['wordpress-install-dir'];
				}
			}

			if ( isset( $json['extra']['wordpress-content-dir'] ) ) {
				$this->wp_content_path = $json['extra']['wordpress-content-dir'];
			}
		}
	}

	public function get_wp() {

	}

	public function get_database() {
		// TODO: Implement get_database() method.
	}

	public function get_config() {
		// TODO: Implement get_config() method.
	}

	public function get_path() {
		// TODO: Implement get_path() method.
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
	public function set_custom_wp_content() {

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
	public function set_custom_wp_path() {

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