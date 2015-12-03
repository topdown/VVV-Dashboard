<?php

define( 'VVV_DASH_BASE', true );
define( 'VVV_WEB_ROOT', '/srv/www' );
define( 'VVV_DASH_VERSION', '0.1.3' );

// Settings
$path = '../../';
include_once '../dashboard-custom.php';
include_once 'libs/vvv-dash-cache.php';
include_once 'libs/vvv-dash-hosts.php';
include_once 'libs/vvv-dashboard.php';
include_once 'libs/functions.php';

// Make sure everything is ready
vvv_dash_prep();

$backup_status   = false;
$plugins         = '';
$themes          = '';
$host            = '';
$purge_status    = '';
$debug_log       = '';
$debug_log_lines = '';
$debug_log_path  = '';
$cache           = new vvv_dash_cache();
$vvv_dash        = new vvv_dashboard();

if ( ( $hosts = $cache->get( 'host-sites', VVV_DASH_HOSTS_TTL ) ) == false ) {

	$hosts  = get_hosts( $path );
	$status = $cache->set( 'host-sites', serialize( $hosts ) );
}

if ( is_string( $hosts ) ) {
	$hosts = unserialize( $hosts );
}

if ( isset( $_GET['host'] ) && isset( $_GET['themes'] ) || isset( $_GET['host'] ) && isset( $_GET['plugins'] ) ) {

	$host_info = $vvv_dash->set_host_info( $_GET['host'] );
	$is_env    = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;

	// WP Starter
	if ( $is_env ) {
		$host_path = '/public/wp';
	} else {
		// Normal WP
		$host_path = $host_info['path'];
	}

	if ( isset( $_GET['host'] ) && isset( $_GET['themes'] ) ) {

		$host_info = $vvv_dash->set_host_info( $_GET['host'] );
		$themes    = get_themes( $host_info['host'], $host_path );
	}

	if ( isset( $_GET['host'] ) && isset( $_GET['plugins'] ) ) {

		$host_info = $vvv_dash->set_host_info( $_GET['host'] );
		$plugins   = get_plugins( $host_info['host'], $host_path );
	}
}

if ( isset( $_GET['host'] ) && isset( $_GET['debug_log'] ) ) {

	$type = check_host_type( $_GET['host'] );

	if ( isset( $type['key'] ) ) {

		if ( isset( $type['path'] ) ) {
			$debug_log_path = VVV_WEB_ROOT . '/' . $type['key'] . '/' . $type['path'] . '/wp-content/debug.log';
		} else {
			$debug_log_path = VVV_WEB_ROOT . '/' . $type['key'] . '/wp-content/debug.log';
		}

	} else {
		$host           = strstr( $_GET['host'], '.', true );
		$debug_log_path = VVV_WEB_ROOT . '/' . $host . '/htdocs/wp-content/debug.log';
	}

	if ( $debug_log_path && file_exists( $debug_log_path ) ) {
		$debug_log = get_php_errors( 21, 140, $debug_log_path );
	}

	if ( is_array( $debug_log ) ) {
		$debug_log_lines = format_php_errors( $debug_log );
	}
}


if ( isset( $_POST ) ) {

	if ( isset( $_POST['backup'] ) && isset( $_POST['host'] ) ) {

		$host_info = $vvv_dash->set_host_info( $_POST['host'] );
		$is_env    = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;

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
			$backup_status = vvv_dash_wp_backup( $_POST['host'] );
		}

	}

	if ( isset( $_POST['purge_hosts'] ) ) {
		$purge_status = $cache->purge( 'host-sites' );
	}

	if ( isset( $_POST['purge_themes'] ) ) {
		$purge_status = $cache->purge( '-themes' );
	}

	if ( isset( $_POST['purge_plugins'] ) ) {
		$purge_status = $cache->purge( '-plugins' );
	}

	if ( isset( $_POST['update_item'] ) ) {

		if ( isset( $_POST['host'] ) ) {

			$type = check_host_type( $_POST['host'] );

			if ( isset( $type['key'] ) ) {

				if ( isset( $type['path'] ) ) {

					if ( ! empty( $_POST['type'] ) && 'plugins' == $_POST['type'] ) {
						$update_status = shell_exec( 'wp plugin update ' . $_POST['item'] . ' --path=' . VVV_WEB_ROOT . '/' . $type['key'] . $type['path'] );
						$purge_status  = $_POST['item'] . ' was updated!<br />';
						$purge_status .= $cache->purge( '-plugins' );
					}

					if ( ! empty( $_POST['type'] ) && 'themes' == $_POST['type'] ) {
						$update_status = shell_exec( 'wp theme update ' . $_POST['item'] . ' --path=' . VVV_WEB_ROOT . '/' . $type['key'] . $type['path'] );
						$purge_status  = $_POST['item'] . ' was updated!<br />';
						$purge_status .= $cache->purge( '-themes' );
					}

				} else {

					if ( ! empty( $_POST['type'] ) && 'plugins' == $_POST['type'] ) {
						$update_status = shell_exec( 'wp plugin update ' . $_POST['item'] . ' --path=' . VVV_WEB_ROOT . '/' . $type['key'] . '/' );
						$purge_status  = $_POST['item'] . ' was updated!<br />';
						$purge_status .= $cache->purge( '-plugins' );
					}

					if ( ! empty( $_POST['type'] ) && 'themes' == $_POST['type'] ) {
						$update_status = shell_exec( 'wp theme update ' . $_POST['item'] . ' --path=' . VVV_WEB_ROOT . '/' . $type['key'] . '/' );
						$purge_status  = $_POST['item'] . ' was updated!<br />';
						$purge_status .= $cache->purge( '-themes' );
					}
				}

			} else {
				$host_info = $vvv_dash->set_host_info( $_POST['host'] );
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
					$purge_status .= $cache->purge( '-plugins' );
				}

				if ( ! empty( $_POST['type'] ) && 'themes' == $_POST['type'] ) {
					$update_status = shell_exec( 'wp theme update ' . $_POST['item'] . ' --path=' . $host_path );
					$purge_status  = $_POST['item'] . ' was updated!<br />';
					$purge_status .= $cache->purge( '-themes' );
				}


			}
		}
	}
}

include_once 'views/header.php';
include_once 'views/navbar.php';
?>
	<div class="container-fluid">

<?php include_once 'views/sidebar.php' ?>

	<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">

		<?php

		purge_status( $purge_status );

		if ( VVV_DASH_VERSION < version_check() ) {
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<p>A new version of <em> VVV Dashboard</em> is available.
				<br />Your current version: <?php echo VVV_DASH_VERSION ?><br />
				<strong> New version: <?php echo version_check(); ?></strong></p>
			</div><?php
		}

		if ( $backup_status ) {
			echo $backup_status;
		}
		?>
		<h1 class="page-header"><i class="fa fa-tachometer"></i> VVV Dashboard</h1>

		<div class="row">
			<div class="col-sm-12 hosts">
				<?php

				$close = '<a class="btn btn-primary btn-xs" href="./">Close</a>';
				if ( ! empty( $plugins ) ) {
					if ( isset( $_GET['host'] ) ) {
						?><h4>The plugin list for
						<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

						echo format_table( $plugins, $_GET['host'], 'plugins' );
					}

				}

				if ( ! empty( $themes ) ) {
					if ( isset( $_GET['host'] ) ) {
						?><h4>The theme list for
						<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

						echo format_table( $themes, $_GET['host'], 'themes' );
					}
				}

				if ( $debug_log_path && file_exists( $debug_log_path ) && ! empty( $debug_log_lines ) ) {

					if ( isset( $_GET['host'] ) ) {
						?><h4>Debug Log for
						<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

						?>
						<div class="wp-debug-log">
							<?php echo $debug_log_lines; ?>
						</div>
						<?php
					}
				}

				?>
				<p>
					<small>Note: To profile, <code>xdebug_on</code> must be set.</small>
					<?php $xdebug = ( extension_loaded( 'xdebug' ) ? true : false );
					if ( $xdebug ) {
						?>
						xDebug is currently <span class="label label-success">on</span>
						<?php
					} else {
						?>
						xDebug is currently <span class="label label-danger">off</span>
						<?php
					} ?>
				</p>
				<div id="search_container" class="input-group search-box">
					<span class="input-group-addon"> <i class="fa fa-search"></i> </span>
					<input type="text" class="form-control search-input" id="text-search" placeholder="Live Search..." />
					<span class="input-group-addon"> Hosts
						<span class="badge"><?php echo isset( $hosts['site_count'] ) ? $hosts['site_count'] : ''; ?></span>
					</span>
				</div>


				<table class="sites table table-responsive table-striped">
					<thead>
					<tr>
						<th>Debug Mode</th>
						<th>Sites</th>
						<th>WP Version</th>
						<th>Actions</th>
					</tr>
					</thead>
					<?php
					foreach ( $hosts as $key => $array ) {
						if ( 'site_count' != $key ) {

							$host_info = $vvv_dash->set_host_info( $array['host'] );
							$is_env    = ( isset( $host_info['is_env'] ) ) ? $host_info['is_env'] : false;

							$dash_hosts    = new vvv_dash_hosts();
							$has_wp_config = $dash_hosts->wp_config_exists( $host_info );

							if ( $is_env ) {
								$env_configs = $dash_hosts->get_wp_starter_configs( $host_info );
								$env         = ( isset( $env_configs[ $host_info['host'] ]["WORDPRESS_ENV"] ) ) ? $env_configs[ $host_info['host'] ]["WORDPRESS_ENV"] : false;

								if ( $env ) {
									$configs        = $env_configs[ $host_info['host'] ][ $env ];
									$array['is_wp'] = true;
									$array['debug'] = $configs['WP_DEBUG'];
								}
							}

							?>
							<tr>
								<?php if ( 'true' == $array['debug'] ) { ?>
									<td><span class="label label-success">Debug On <i class="fa fa-check-circle-o"></i></span>
									</td>
								<?php } else {
									if ( $has_wp_config || $is_env ) {
										?>
										<td><span class="label label-danger">Debug Off
												<i class="fa fa-times-circle-o"></i></span></td>
										<?php
									} else {
										?>
										<td><span class="label label-danger">NOT INSTALLED</td>
										<?php
									}
								} ?>

								<td><?php echo $array['host']; ?></td>
								<td><?php
									if ( isset( $array['version'] ) ) {
										echo $array['version'];
									} else {
										echo 'N/A';
									}
									?></td>

								<td>
									<a class="btn btn-primary btn-xs" href="http://<?php echo $array['host']; ?>/" target="_blank">Visit Site
										<i class="fa fa-external-link"></i></a>

									<?php if ( 'true' == $array['is_wp'] && $has_wp_config ) { ?>
										<a class="btn btn-warning btn-xs" href="http://<?php echo $array['host']; ?>/wp-admin" target="_blank">Admin/Login
											<i class="fa fa-wordpress"></i></a>
									<?php } ?>
									<a class="btn btn-success btn-xs" href="http://<?php echo $array['host']; ?>/?XDEBUG_PROFILE" target="_blank">Profiler
										<i class="fa fa-search-plus"></i></a>

									<?php if ( $is_env || $has_wp_config ) { ?>
										<form class="get-themes" action="" method="get">
											<input type="hidden" name="host" value="<?php echo $array['host']; ?>" />
											<input type="hidden" name="get_themes" value="true" />
											<input type="submit" class="btn btn-default btn-xs" name="themes" value="Themes" />
										</form>

										<form class="get-plugins" action="" method="get">
											<input type="hidden" name="host" value="<?php echo $array['host']; ?>" />
											<input type="hidden" name="get_plugins" value="true" />
											<input type="submit" class="btn btn-default btn-xs" name="plugins" value="Plugins" />
										</form>

									<?php }
									if ( $is_env || $has_wp_config ) {
										?>

										<form class="backup" action="" method="post">
											<input type="hidden" name="host" value="<?php echo $array['host']; ?>" />
											<input type="submit" class="btn btn-danger btn-xs" name="backup" value="Backup DB" />
										</form>
										<?php
									}

									$type = check_host_type( $array['host'] );

									if ( isset( $type['key'] ) ) {

										if ( isset( $type['path'] ) ) {
											$debug_log_path = VVV_WEB_ROOT . '/' . $type['key'] . '/' . $type['path'] . '/wp-content/debug.log';
										} else {
											$debug_log_path = VVV_WEB_ROOT . '/' . $type['key'] . '/wp-content/debug.log';
										}

									} else {
										$this_host      = strstr( $array['host'], '.', true );
										$debug_log_path = VVV_WEB_ROOT . '/' . $this_host . '/htdocs/wp-content/debug.log';
									}

									if ( file_exists( $debug_log_path ) ) { ?>
										<form class="backup" action="" method="get">
											<input type="hidden" name="host" value="<?php echo $array['host']; ?>" />
											<input type="submit" class="btn btn-warning btn-xs" name="debug_log" value="Debug Log" />
										</form>
									<?php } ?>
								</td>
							</tr>
							<?php
						}
					}
					unset( $array ); ?>
				</table>
			</div>
		</div>

		<div class="error_logs"><h3>Last 10 PHP Errors</h3>
			<?php
			$lines = get_php_errors();
			$lines = format_php_errors( $lines );

			echo $lines;

			include_once 'views/commands-table.php';
			?>
		</div>
	</div>
<?php

include_once 'views/footer.php';