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


	public function get_tables() {
		$command = shell_exec( 'wp db tables --all-tables --path=' . $this->host_info['wp_path'] );

		if ( $command ) {
			return $command;
		} else {
			return false;
		}
	}

	public function database_check() {
		$command = shell_exec( 'wp db check --path=' . $this->host_info['wp_path'] );

		if ( $command ) {
			return $command;
		} else {
			return false;
		}
	}

	public function database_optimize() {
		$command = shell_exec( 'wp db optimize --path=' . $this->host_info['wp_path'] );

		if ( $command ) {
			return $command;
		} else {
			return false;
		}
	}

	public function database_repair() {
		$command = shell_exec( 'wp db repair --path=' . $this->host_info['wp_path'] );

		if ( $command ) {
			return $command;
		} else {
			return false;
		}
	}

	public function database_reset() {


		// Site options
		//$siteurl     = shell_exec( 'wp option get siteurl --path=' . $this->host_info['wp_path'] );
		//$home        = shell_exec( 'wp option get home --path=' . $this->host_info['wp_path'] );
		//$blogname    = shell_exec( 'wp option get blogname --path=' . $this->host_info['wp_path'] );
		//$admin_email = shell_exec( 'wp option get admin_email --path=' . $this->host_info['wp_path'] );
		//$template    = shell_exec( 'wp option get template --path=' . $this->host_info['wp_path'] );
		//$stylesheet  = shell_exec( 'wp option get stylesheet --path=' . $this->host_info['wp_path'] );

		// Dump all tables
		$status = shell_exec( 'wp db reset --yes --path=' . $this->host_info['wp_path'] );

		// Reinstall WordPress
		$status .= shell_exec( 'wp core install --path=' . $this->host_info['wp_path'] . ' --url=http://' . $this->host_info['domain'] . ' --title=' . $this->host_info['hostname'] . ' --admin_user=admin --admin_password=password --admin_email=admin@localhost.dev' );

		//$add_options = 'wp option add my_option foobar';
		//$status .= shell_exec( 'wp option add siteurl ' . $siteurl . ' --path=' . $this->host_info['wp_path'] );
		//$status .= shell_exec( 'wp option add home ' . $home . ' --path=' . $this->host_info['wp_path'] );
		//$status .= shell_exec( 'wp option add blogname ' . $blogname . ' --path=' . $this->host_info['wp_path'] );
		//$status .= shell_exec( 'wp option add admin_email ' . $admin_email . ' --path=' . $this->host_info['wp_path'] );
		//$status .= shell_exec( 'wp option add template ' . $template . ' --path=' . $this->host_info['wp_path'] );
		//$status .= shell_exec( 'wp option add stylesheet ' . $stylesheet . ' --path=' . $this->host_info['wp_path'] );

		if ( $status ) {
			return $status;
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


	public function migrate() {

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

					$file_name = 'dumps/migrated-' . $host . '-' . $domain . '_' . date( 'm-d-Y_g-i-s', time() ) . '.sql';
					$status    = $this->create_db_backup( $host, $file_name );

					if ( $status ) {
						echo $status;
					}

					$migrate       = preg_split( "[\r|\n]", trim( $migrate ) );
					$m_table_array = array();
					$close         = '<a class="close" href="./?page=tools&host=' . $host . '">Close</a>';
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
					echo vvv_dash_notice( 'You can rollback the database to its normal state from the backups below.' );

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
			//include_once VVV_DASH_VIEWS . '/forms/migrate.php';
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

	/**
	 * @ToDo           move this to the commands/database and views/database
	 *
	 * Get all backups and list them in a nice table
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/3/15, 1:10 AM
	 *
	 * @return string
	 */
	function get_host_backups_table() {

		if ( ! isset( $_GET['host'] ) ) {
			return false;
		} else {
			$current_host = $_GET['host'];
		}

		$this->_maybe_delete_backup();

		$backups    = dir_to_array( VVV_WEB_ROOT . '/default/dashboard/dumps' );
		$file_info  = array();
		$table_data = array();
		$table      = '';

		if ( ! empty( $notice ) ) {
			$table .= vvv_dash_notice( $notice );
		}

		$table .= '<table class="table table-responsive table-striped table-bordered table-hover backups">';
		$table .= '<thead><tr>';
		$table .= '<th>Host</th>';
		$table .= '<th>Date <small>( M-D-Y )</small></th>';
		$table .= '<th>Time <small>( H : M : S )</small></th>';
		$table .= '<th>Actions</th>';
		$table .= '</tr></thead>';

		foreach ( $backups as $key => $file_path ) {

			$current_hostname               = str_replace( '.dev', '', $current_host );
			$file                           = str_replace( 'dumps/', '', strstr( $file_path, 'dumps/' ) );
			$file_info[ $key ]['file']      = $file;
			$file_info[ $key ]['file_path'] = VVV_WEB_ROOT . '/default/dashboard/dumps/' . $file;
			$file_parts                     = explode( '_', $file );
			$file_info[ $key ]['file_host'] = $file_parts[0];
			$file_info[ $key ]['file_date'] = $file_parts[1];
			$file_info[ $key ]['file_time'] = str_replace( '.sql', '', $file_parts[2] );

			$host = ( strpos( $file_parts[0], '.dev' ) == true ) ? $file_parts[0] :
				( strpos( $file_parts[0], '.com' ) == true ) ? $file_parts[0] : $file_parts[0] . '.dev';

			// So we can check for migration files
			$parts = explode( '-', $file_parts[0] );

			if ( isset( $parts[1] ) && $current_host == $parts[1] || $host == $current_host) {

				// Table data
				$table_data[] .= '<tr>';
				$table_data[] .= '<td class="host">' . $host . '</td>';
				$table_data[] .= '<td>' . $file_parts[1] . '</td>';
				$table_data[] .= '<td>' . str_replace( array( '.sql', '-' ), array(
						'',
						':'
					), $file_parts[2] ) . '</td>';
				$table_data[]
					.= '<td>
<a class="btn btn-primary btn-xs" href="dumps/' . $file . '">Save As</a>
<form class="" action="" method="post">
<input type="hidden" name="get_backups" value="Backups" />
<input type="hidden" name="host" value="' . $host . '" />
<input type="hidden" name="file_path" value="' . urlencode( $file_info[ $key ]['file_path'] ) . '" />
';
				if ( strpos( $file_parts[0], 'migrated-' ) === false ) {
					$table_data[] .= '<input type = "submit" class="btn btn-warning btn-xs" name = "roll_back" value = "Roll Back" /> ';
				}
				$table_data[]
					.= '<input type="submit" class="btn btn-danger btn-xs" name="delete_backup" value="Delete" />
</form>

</td>';
				$table_data[] .= '</tr>';
			} // end foreach
		}

		$table .= implode( '', $table_data );
		$table .= '</table>';

		return $table;
	}

	/**
	 * @ToDo           move this to the commands/database and views/database
	 *
	 * Get all backups and list them in a nice table
	 *
	 * @author         Jeff Behnke <code@validwebs.com>
	 * @copyright  (c) 2009-15 ValidWebs.com
	 *
	 * Created:    12/3/15, 1:10 AM
	 *
	 * @return string
	 */
	public function get_backups_table() {

		$this->_maybe_delete_backup();

		$backups    = dir_to_array( VVV_WEB_ROOT . '/default/dashboard/dumps' );
		$file_info  = array();
		$table_data = array();
		$table      = '';

		if ( ! empty( $notice ) ) {
			$table .= vvv_dash_notice( $notice );
		}

		$table .= '<table class="table table-responsive table-striped table-bordered table-hover backups">';
		$table .= '<thead><tr>';
		$table .= '<th>Host</th>';
		$table .= '<th>Date <small>( M-D-Y )</small></th>';
		$table .= '<th>Time <small>( H : M : S )</small></th>';
		$table .= '<th>Actions</th>';
		$table .= '</tr></thead>';

		foreach ( $backups as $key => $file_path ) {

			$file = str_replace( 'dumps/', '', strstr( $file_path, 'dumps/' ) );

			$file_info[ $key ]['file']      = $file;
			$file_info[ $key ]['file_path'] = VVV_WEB_ROOT . '/default/dashboard/dumps/' . $file;
			$file_parts                     = explode( '_', $file );
			$file_info[ $key ]['file_host'] = $file_parts[0];
			$file_info[ $key ]['file_date'] = $file_parts[1];
			$file_info[ $key ]['file_time'] = str_replace( '.sql', '', $file_parts[2] );

			// @ToDo this (.com) is a bug waiting to happen
			$host = ( strpos( $file_parts[0], '.dev' ) == true ) ? $file_parts[0] :
				( strpos( $file_parts[0], '.com' ) == true ) ? $file_parts[0] : $file_parts[0] . '.dev';

			// Table data
			$table_data[] .= '<tr>';

			if ( strpos( $file_parts[0], 'migrated-' ) !== false ) {
				$table_data[] .= '<td class="host">' . $host . '</td>';
			} else {
				$table_data[] .= '<td class="host"><a href="./?page=tools&host=' . $host . '">' . $host . '</a></td>';
			}

			$table_data[] .= '<td>' . $file_parts[1] . '</td>';
			$table_data[] .= '<td>' . str_replace( array( '.sql', '-' ), array( '', ':' ), $file_parts[2] ) . '</td>';
			$table_data[]
				.= '<td>
<a class="btn btn-primary btn-xs" href="dumps/' . $file . '">Save As</a>
<form class="" action="" method="post">
<input type="hidden" name="get_backups" value="Backups" />
<input type="hidden" name="host" value="' . $host . '" />
<input type="hidden" name="file_path" value="' . urlencode( $file_info[ $key ]['file_path'] ) . '" />
';
			if ( strpos( $file_parts[0], 'migrated-' ) === false ) {
				$table_data[] .= '<input type = "submit" class="btn btn-warning btn-xs" name = "roll_back" value = "Roll Back" /> ';
			}
			$table_data[]
				.= '<input type="submit" class="btn btn-danger btn-xs" name="delete_backup" value="Delete" />
</form>

</td>';
			$table_data[] .= '</tr>';
		} // end foreach

		$table .= implode( '', $table_data );
		$table .= '</table>';

		return $table;
	}

	private function _maybe_delete_backup() {
		if ( isset( $_POST['file_path'] ) ) {
			if ( isset( $_POST['delete_backup'] ) && $_POST['delete_backup'] == 'Delete' ) {
				$file = urldecode( $_POST['file_path'] );
				if ( file_exists( $file ) ) {

					// @ToDo verify with the user (Are you sure!)
					unlink( $file );

					$notice = $file . ' was deleted.';
				}
			}

			// @ToDo create roll back function
			//		if ( isset( $_POST['roll_back'] ) && $_POST['roll_back'] == 'Roll Back' ) {
			//			$notice = 'DB roll backs are not quite ready yet. Will be coming in a release soon!';
			//		}
		}
	}

}

// End database.php