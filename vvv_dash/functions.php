<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/19/15, 11:53 AM
 *
 * LICENSE:
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * functions.php
 */

function vvv_dash_prep() {
	if ( ! is_dir( VVV_WEB_ROOT . '/default/dashboard/cache' ) ) {
		mkdir( VVV_WEB_ROOT . '/default/dashboard/cache' );
	}

	if ( ! is_dir( VVV_WEB_ROOT . '/default/dashboard/dumps' ) ) {
		mkdir( VVV_WEB_ROOT . '/default/dashboard/dumps' );
	}
}


/**
 * Simply displays the purge status alert
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    11/20/15, 12:32 AM
 *
 * @param $purge_status
 */
function purge_status( $purge_status ) {
	if ( $purge_status ) { ?>
		<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?php echo $purge_status ?> files were purged from cache!
		</div><?php
	}
}

/**
 * Notice for dashboard
 *
 * @ToDo           convert the others to use this one
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    11/22/15, 2:40 PM
 *
 * @param        $message
 * @param string $cookie_name
 * @param int    $cookie_time default is 30 days
 *
 * @return bool|string
 */
function vvv_dash_notice( $message, $cookie_name = '', $cookie_time = 30 ) {

	$notice = false;
	$cookie = ( isset( $_COOKIE[ $cookie_name ] ) ) ? $_COOKIE[ $cookie_name ] : false;

	if ( $message ) {

		if ( ! $cookie && ! empty( $cookie_name ) ) {
			//setcookie( $cookie_name, 'true', strtotime( '+' . $cookie_time . ' days' ) );
		}

		$notice
			= '<div class="alert alert-success alert-dismissible" role="alert">' .
			  $cookie . '
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>' .
			  $message . '
		</div>';
	}

	if ( ! $cookie ) {
		return $notice;
	} else {
		return false;
	}
}

/**
 * Error notice for dashboard
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    12/16/15, 12:13 PM
 *
 * @param        $message
 * @param string $cookie_name
 * @param int    $cookie_time default is 30 days
 *
 * @return bool|string
 */
function vvv_dash_error( $message, $cookie_name = '', $cookie_time = 30 ) {

	$notice = false;
	$cookie = ( isset( $_COOKIE[ $cookie_name ] ) ) ? $_COOKIE[ $cookie_name ] : false;

	if ( $message ) {

		if ( ! $cookie && ! empty( $cookie_name ) ) {
			//setcookie( $cookie_name, 'true', strtotime( '+' . $cookie_time . ' days' ) );
		}

		$notice
			= '<div class="alert alert-danger alert-dismissible" role="alert">' .
			  $cookie . '
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>' .
			  $message . '
		</div>';
	}

	if ( ! $cookie ) {
		return $notice;
	} else {
		return false;
	}
}

/**
 * Formats csv data strings into bootstrap tables
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    11/19/15, 12:42 PM
 *
 * @param        $data
 *
 * @param        $host
 * @param string $type of data Plugin || Theme
 *
 * @return string
 */
function format_table( $data, $host, $type = '' ) {

	$table_data       = array();
	$table            = '<table class="table table-responsive table-striped table-bordered table-hover">';
	$data             = explode( "\n", $data );
	$update_form      = '';
	$child_theme_form = '';

	foreach ( $data as $key => $line ) {

		if ( '0' == $key ) {
			$row = explode( ',', $line );

			$table_data[] .= '<thead><tr>';
			$table_data[] .= '</th><th>' . implode( '<th>', $row ) . '</th>';
			$table_data[] .= '</tr></thead>';
		} else {
			$row = explode( ',', $line );
			if ( isset( $row[0] ) && ! empty( $row[0] ) ) {
				$table_data[] .= '<tr>';

				foreach ( $row as $index => $cell ) {

					if ( $index == 0 ) {
						$update_form
							= '<form class="update-items" action="" method="post">
								<input type="hidden" name="host" value="' . $host . '" />
								<input type="hidden" name="item" value="' . $cell . '" />
								<input type="hidden" name="type" value="' . $type . '" />
								<input type="submit" class="btn btn-default btn-xs" name="update_item" value="Update" />
							</form>';

						if ( $type == 'themes' ) {
							$child_theme_form
								= '<form class="create-child" action="" method="post">
								<input type="hidden" name="host" value="' . $host . '" />
								<input type="hidden" name="item" value="' . $cell . '" />
								<input type="hidden" name="type" value="' . $type . '" />
								<input class="child-name" placeholder="Theme Name" type="text" name="theme_name" value="" />
								<input class="child-slug" placeholder="theme_slug" type="text" name="child" value="" />
								<button type="submit" class="btn btn-primary btn-xs" name="create_child" value="Create Child">
								<i class="fa fa-paint-brush"></i> Create Child
								</button>
							</form>
							';
						}

					}

					if ( 'active' == $cell || 'parent' == $cell ) {
						$table_data[] .= '<td class="activated">' . $cell . '</td>';
					} elseif ( 'available' == $cell ) {
						$table_data[] .= '<td class="update">' . $cell . $update_form . '</td>';
					} else {
						if ( $index == 0 ) {
							$table_data[] .= '<td>' . $cell . ' ' . $child_theme_form . '</td>';
						} else {
							$table_data[] .= '<td>' . $cell . '</td>';
						}
					}

				} // end foreach
				unset( $cell );
				$table_data[] .= '</tr>';
			}
		}
	} // end foreach

	$table .= implode( '', $table_data );
	$table .= '</table>';


	return $table;
}

/**
 * Handle CSV data
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    5/9/16, 4:12 PM
 *
 * @param     $data
 * @param int $column
 *
 * @return array
 */
function get_csv_names( $data, $column = 0 ) {

	$names = array();
	$data  = explode( "\n", $data );

	foreach ( $data as $key => $line ) {

		if ( '0' == $key ) {
			$row = explode( ',', $line );

		} else {
			$row = explode( ',', $line );
			if ( isset( $row[ $column ] ) && ! empty( $row[0] ) ) {

				foreach ( $row as $index => $cell ) {

					if ( $index == $column ) {

						$names[] = $cell;

					}


				} // end foreach
				unset( $cell );

			}
		}
	} // end foreach

	return $names;
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
function get_backups_table() {

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

	$backups    = dir_to_array( VVV_WEB_ROOT . '/default/dashboard/dumps' );
	$file_info  = array();
	$table_data = array();
	$table      = '';

	if ( ! empty( $notice ) ) {
		$table .= vvv_dash_notice( $notice );
	}

	$table .= '<table class="table table-responsive table-striped table-bordered table-hover">';
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

		$host = ( strpos( $file_parts[0], '.dev' ) == true ) ? $file_parts[0] :
			( strpos( $file_parts[0], '.com' ) == true ) ? $file_parts[0] : $file_parts[0] . '.dev';

		// Table data
		$table_data[] .= '<tr>';
		$table_data[] .= '<td>' . $host . '</td>';
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
		if ( strpos( $file_parts[0], '.com' ) == false ) {
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

/**
 * Fetch the PHP error log from the machine
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    11/20/15, 12:38 PM
 *
 * @param int    $linecount
 * @param int    $length
 * @param string $file
 * @param int    $offset_factor we double the offset factor on each iteration
 *                              if our first guess at the file offset doesn't
 *                              yield $linecount lines
 *
 * @return array
 */
function get_php_errors( $linecount = 11, $length = 140, $file = "/srv/log/php_errors.log", $offset_factor = 1 ) {

	$lines = array();
	$bytes = filesize( $file );
	$fp = fopen( $file, "r" ) or die( "Can't open $file" );


	$complete = false;
	while ( ! $complete ) {
		//seek to a position close to end of file
		$offset = $linecount * $length * $offset_factor;
		fseek( $fp, - $offset, SEEK_END );


		//we might seek mid-line, so read partial line
		//if our offset means we're reading the whole file,
		//we don't skip...
		if ( $offset < $bytes ) {
			fgets( $fp );
		}

		//read all following lines, store last x
		$lines = array();
		while ( ! feof( $fp ) ) {
			$line = fgets( $fp );
			array_push( $lines, $line );
			if ( count( $lines ) > $linecount ) {
				array_shift( $lines );
				$complete = true;
			}
		}

		//if we read the whole file, we're done, even if we
		//don't have enough lines
		if ( $offset >= $bytes ) {
			$complete = true;
		} else {
			$offset_factor *= 2;
		} //otherwise let's seek even further back

	}
	fclose( $fp );

	return $lines;
}

/**
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    11/20/15, 12:40 PM
 *
 * @param array $lines
 *
 * @return string
 */
function format_php_errors( $lines = array() ) {

	$lines = array_filter( $lines );
	$lines = array_reverse( $lines );
	$html  = implode( '', $lines );
	$html  = str_replace(
		array(
			"\n",
			'[',
			']',
			'on line ',
			' Parse error:',
			'PHP Warning:',
			' Fatal error:',
			' in /',
		),
		array(
			'</span></p>',
			'<p><span class="time">[',
			']</span> <span class="error-type">',
			'</span><span class="line"> on line ',
			' Parse error:</span> ',
			'PHP Warning:</span> ',
			' Fatal error:</span> ',
			' <br />in <span class="in-file">/'
		), $html );

	return $html;
}

/**
 * Fetch the data from URL
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    11/20/15, 6:27 PM
 *
 * @param $url
 *
 * @return mixed|string
 */
function get_external_data( $url ) {

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, true );
	curl_setopt( $ch, CURLOPT_USERAGENT, 'VVV Dashboard' );

	if ( curl_exec( $ch ) === false ) {
		$data = "Error: " . curl_error( $ch );
	} else {
		$data = curl_exec( $ch );
	}

	curl_close( $ch );

	return $data;
}

/**
 * Collect the version info from the repo and cache it.
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    11/20/15, 6:27 PM
 *
 * @return bool|mixed|string
 */
function version_check() {

	$cache = new \vvv_dash\cache();

	if ( ( $version = $cache->get( 'version-cache', VVV_DASH_THEMES_TTL ) ) == false ) {
		global $branch;
		$branch  = explode( "\n", trim( $branch ) );
		$branch  = ( isset( $branch[1] ) ) ? $branch[1] : 'master';
		$url     = 'https://raw.githubusercontent.com/topdown/VVV-Dashboard/' . $branch . '/version.txt';
		$version = get_external_data( $url );

		// Don't save unless we have data
		if ( $version && ! strstr( $version, 'Error' ) ) {
			$status = $cache->set( 'version-cache', $version );
		}
	}

	return $version;
}

/**
 * If we have an update available get the new-features list
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    12/3/15, 12:38 PM
 *
 * @return bool|mixed|string
 */
function vvv_dash_get_latest_features() {

	$cache = new \vvv_dash\cache();

	if ( ( $new_features = $cache->get( 'newfeatures-cache', VVV_DASH_THEMES_TTL ) ) == false ) {
		global $branch;
		$branch       = explode( "\n", trim( $branch ) );
		$branch       = ( isset( $branch[1] ) ) ? $branch[1] : 'master';
		$url          = 'https://raw.githubusercontent.com/topdown/VVV-Dashboard/' . $branch . '/new-features.txt';
		$new_features = get_external_data( $url );
		// Don't save unless we have data
		if ( $new_features && ! strstr( $new_features, 'Error' ) ) {
			$status = $cache->set( 'newfeatures-cache', $new_features );
		}
	}

	return $new_features;
}

/**
 * Format the new feature list
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    12/3/15, 12:39 PM
 *
 * @return bool|mixed|string
 */
function vvv_dash_new_features() {
	$features = vvv_dash_get_latest_features();

	$features = str_replace(
		array(
			'    *',
			'  *',
			'*'
		),
		array(
			'<br /> --------- ',
			'<br />  --- ',
			'<br /> '
		),
		$features );

	return $features;
}


/**
 * Get an array that represents directory tree
 *
 * @param string $directory Directory path
 * @param bool   $recursive Include sub directories
 * @param bool   $listDirs  Include directories on listing
 * @param bool   $listFiles Include files on listing
 * @param string $exclude   Exclude paths that matches this regex
 *
 * @return array
 */
function dir_to_array( $directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '' ) {
	$arrayItems    = array();
	$skipByExclude = false;
	$handle        = opendir( $directory );
	if ( $handle ) {
		while ( false !== ( $file = readdir( $handle ) ) ) {
			preg_match( "/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip );
			if ( $exclude ) {
				preg_match( $exclude, $file, $skipByExclude );
			}
			if ( ! $skip && ! $skipByExclude ) {
				if ( is_dir( $directory . DIRECTORY_SEPARATOR . $file ) ) {
					if ( $recursive ) {
						$arrayItems = array_merge( $arrayItems, dir_to_array( $directory . DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude ) );
					}
					if ( $listDirs ) {
						$file         = $directory . DIRECTORY_SEPARATOR . $file;
						$arrayItems[] = $file;
					}
				} else {
					if ( $listFiles ) {
						$file         = $directory . DIRECTORY_SEPARATOR . $file;
						$arrayItems[] = $file;
					}
				}
			}
		}
		closedir( $handle );
	}

	return $arrayItems;
}

/**
 * Just to clean up the index.php file some
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2009-15 ValidWebs.com
 *
 * Created:    12/7/15, 3:59 PM
 *
 */
function vvv_dash_xdebug_status() {
	?>
	<p>
		<small>Note: To profile, <code>xdebug_on</code> must be set.</small>
		<?php $xdebug = ( extension_loaded( 'xdebug' ) ? true : false );
		if ( $xdebug ) {
			?>
			<span class="pull-right small">xDebug is currently <span class="label label-success">on</span></span>
			<?php
		} else {
			?>
			<span class="pull-right small">xDebug is currently <span class="label label-danger">off</span></span>
			<?php
		} ?>
	</p>
	<?php
}
// End functions.php