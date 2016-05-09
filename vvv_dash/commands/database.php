<?php

/**
 *
 * PHP version 5
 *
 * Created: 2/19/16, 4:14 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * database.php
 */

namespace vvv_dash\commands;

/**
 * Actions that happen on the WP databases
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Class database
 * @package        vvv_dash\commands
 */
class database extends host {


	/**
	 *
	 * @ToDo           get rid of $path param
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/20/16, 1:54 AM
	 *
	 * @param $path
	 * @param $file_name
	 *
	 * @return bool|string
	 */
	public function backup( $path, $file_name ) {

		$export = shell_exec( 'wp db export --add-drop-table --path=' . $this->host_info['wp_path'] . ' ' . $file_name );

		if ( $export ) {
			return $export;
		} else {
			return false;
		}
	}

	/**
	 *
	 * @ToDo           get rid of $path param
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    2/20/16, 1:55 AM
	 *
	 * @param $path
	 * @param $file
	 *
	 * @return bool|string
	 */
	public function roll_back( $path, $file ) {

		$status = shell_exec( 'wp db import --path=' . $this->host_info['wp_path'] . ' ' . urldecode( $file ) );

		if ( $status ) {
			return $status;
		} else {
			return false;
		}
	}

	/**
	 * Creates a database dump
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/5/15, 2:43 AM
	 *
	 * @param        $host
	 *
	 * @param string $file_name
	 *
	 * @return bool|string
	 */
	public function create_db_backup( $host, $file_name = '' ) {
		$export = false;
		// $this->host_info['hostname'], $this->host_info['wp_path']

		if ( $this->host_info['wp_is_installed'] == 'true' ) {

			if ( ! empty( $file_name ) ) {
				$export = shell_exec( 'wp db export --add-drop-table --path=' . $this->host_info['wp_path'] . ' ' . $file_name );
			} else {
				$file_name = 'dumps/' . $this->host_info['hostname'] . '_' . date( 'm-d-Y_g-i-s', time() ) . '.sql';
				$export    = shell_exec( 'wp db export --add-drop-table --path=' . $this->host_info['wp_path'] . ' ' . $file_name );
			}

			if ( file_exists( $file_name ) ) {
				return vvv_dash_notice( 'Your backup is ready at www/default/dashboard/' . $file_name );
			}
		}

		return $export;
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

		$status = shell_exec( 'wp db import --path=' . $this->host_info['wp_path'] . ' ' . urldecode( $file ) );

		return $status;
	}


	public function display() {

		if ( isset( $_GET['migrate'] ) && isset( $_GET['host'] ) ) {

			$host   = $_GET['host'];
			$domain = ( isset( $_GET['domain'] ) ) ? $_GET['domain'] : false;
			//$host_info = $this->_hosts->set_host_info( $host );
			//$host_path = VVV_WEB_ROOT . '/' . $host_info['host'] . $host_info['path'];

			if ( $domain ) {
				$status = $this->create_db_backup( $host );

				if ( $status ) {
					echo $status;
				}

				$cmd     = 'wp search-replace --url=' . $host . ' ' . $host . ' ' . $domain . ' --path=' . $this->host_info['wp_path'];
				$migrate = shell_exec( $cmd );

				if ( $migrate ) {

					$file_name = 'dumps/migrated-' . $domain . '_' . date( 'm-d-Y_g-i-s', time() ) . '.sql';
					$status    = $this->create_db_backup( $host, $file_name );

					if ( $status ) {
						echo $status;
					}

					$migrate       = preg_split( "[\r|\n]", trim( $migrate ) );
					$m_table_array = array();
					$close         = '<a class="close" href="./">Close</a>';
					$m_table       = '<h4>The tables modified for <span class="red">' . $host . '</span> ' . $close . '</h4>';
					$m_table .= '<table class="table table-bordered table-striped">';

					foreach ( $migrate as $key => $row ) {

						$m_table_array[] = '<tr>';

						$data = explode( "\t", $row );

						if ( 0 == $key ) {
							if ( isset( $data[0] ) ) {
								$m_table_array[] = '<th>' . $data[0] . '</th>';
							}
							if ( isset( $data[1] ) ) {
								$m_table_array[] = '<th>' . $data[1] . '</th>';
							}
							if ( isset( $data[2] ) ) {
								$m_table_array[] = '<th>' . $data[2] . '</th>';
							}
							if ( isset( $data[3] ) ) {
								$m_table_array[] = '<th>' . $data[3] . '</th>';
							}
						} else {

							if ( isset( $data[2] ) && $data[2] > 0 ) {
								if ( isset( $data[0] ) ) {
									$m_table_array[] = '<td>' . $data[0] . '</td>';
								}
								if ( isset( $data[1] ) ) {
									$m_table_array[] = '<td>' . $data[1] . '</td>';
								}
								if ( isset( $data[2] ) ) {
									$m_table_array[] = '<td>' . $data[2] . '</td>';
								}
								if ( isset( $data[3] ) ) {
									$m_table_array[] = '<td>' . $data[3] . '</td>';
								}
							}

						}

						$m_table_array[] = '</tr>';

					} // end foreach

					$m_table .= implode( '', $m_table_array );
					$m_table .= '</table>';

					echo $m_table;
					echo vvv_dash_notice( 'You can rollback the database to its normal state from the backups.' );

				} else {
					echo vvv_dash_error( 'ERROR: Something went wrong, the migration did not happen.' );
				}
			}
			//$file = '';
			// @TODO implement auto roll_back
			//$roll_back = $vvv_dash->db_roll_back( $host, $file );

			// Migration Form
			// @var $host
			// @var $domain
			include_once VVV_DASH_VIEWS . '/forms/migrate.php';
		}
	}

	// @ToDo needs lots of work here, to much happening
	public function migrate() {

		$host   = $_GET['host'];
		$domain = ( isset( $_GET['domain'] ) ) ? $_GET['domain'] : false;
		
		$cmd     = 'wp search-replace --url=' . $host . ' ' . $host . ' ' . $domain . ' --path=' . $this->host_info['wp_path'];
		$migrate = shell_exec( $cmd );

		if ( $migrate ) {

			$file_name = 'dumps/migrated-' . $domain . '_' . date( 'm-d-Y_g-i-s', time() ) . '.sql';
			$status    = $this->backup( $host, $file_name );

			if ( $status ) {
				return $status;
			} else {
				return vvv_dash_error( 'ERROR: Migration data dump failed.' );
			}

		} else {
			return vvv_dash_error( 'ERROR: Something went wrong, the migration did not happen.' );
		}
	}

	public function delete_backup( $file ) {
		if ( file_exists( $file ) ) {

			// @ToDo verify with the user (Are you sure!)
			unlink( $file );

			return $file . ' was deleted.';
		}

		return vvv_dash_error( 'ERROR: file does not exist' );
	}

}
// End database.php