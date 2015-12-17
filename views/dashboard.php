<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/7/15, 3:43 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * dashboard.php
 */
?>
	<div class="row">
		<div class="col-sm-12 hosts">
			<?php

			$close = '<a class="close" href="./">Close</a>';

			// Plugins table
			if ( ! empty( $plugins ) ) {
				if ( isset( $_GET['host'] ) ) {

					$host      = $_GET['host'];
					$host_info = $vvv_dash->set_host_info( $host );
					$host_path = VVV_WEB_ROOT . '/' . $host_info['host'] . $host_info['path'];
					$fav_file  = VVV_WEB_ROOT . '/default/dashboard/favorites/plugins.txt';
					// Install fav plugins
					$checkboxes = $vvv_dash->get_fav_list( $fav_file );

					if ( $checkboxes ) {
						$install_fav_form
							= '<form class="" action="" method="post">
								<p><span class="bold">Install a favorite plugin on this host.</span><br />
								<span class="italic bold red">NOTE: the more plugins you check at one time the longer it takes.</span>
								</p>
								<input type="hidden" name="host" value="' . $host . '" />
								' . $checkboxes . '
								<button type="submit" class="btn btn-success btn-xs" name="install_fav_plugin" value="Install Plugin">
								<i class="fa fa-gears"></i> Install Plugin
								</button>
							</form><br />
							';
						echo $install_fav_form;
					} else {
						echo vvv_dash_error( '<strong>You have no favorite plugins to install.</strong><br />
								Create a file ' . $fav_file . ' with your plugins 1 per line.<br />
								SEE: ' . VVV_WEB_ROOT . '/default/dashboard/favorites/plugins-example.txt', 'no_plugin_fav_list' );
					}

					if ( isset( $_POST['install_fav_plugin'] ) ) {

						$plugin_install_status = $vvv_dash->install_fav_items( $_POST, 'plugin' );

						if ( ! empty( $plugin_install_status ) ) {
							$plugin_install_status = str_replace(PHP_EOL, '<br />', $plugin_install_status);
							echo vvv_dash_notice( $plugin_install_status );
							$host_name    = str_replace( '.dev', '', $_POST['host'] );
							$purge_status = $cache->purge( $host_name . '-plugins' );
							echo vvv_dash_notice( $purge_status . ' files were purged from cache!' );
						}

					}

					// Create New Plugin
					$create_plugin_form
						= '<form class="create-plugin" action="" method="post">
								<input type="hidden" name="host" value="' . $host . '" />

								<p class="plugin-input">
								<label>Plugin Slug</label> <input class="plugin-slug" type="text" placeholder="plugin_slug" name="plugin_slug" value="" />
								 <button class="add-post-type btn btn-default btn-xs">Add Post Type</button>
								</p>

								<p><button type="submit" class="btn btn-success btn-xs" name="create_plugin" value="Create Plugin">
								<i class="fa fa-puzzle-piece"></i> Create Plugin
								</button></p>
							</form><br />
							';
					echo $create_plugin_form;

					if ( isset( $_POST['create_plugin'] ) ) {
						$create_plugin = $vvv_dash->create_plugin( $_POST );
						if ( ! empty( $create_plugin ) ) {
							echo vvv_dash_notice( $create_plugin );
							$host_name    = str_replace( '.dev', '', $_POST['host'] );
							$purge_status = $cache->purge( $host_name . '-plugins' );
							echo vvv_dash_notice( $purge_status . ' files were purged from cache!' );
						}
					}

					?><h4>The plugin list for
					<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

					echo format_table( $plugins, $_GET['host'], 'plugins' );
				}

			}

			if ( isset( $_GET['migrate'] ) && isset( $_GET['host'] ) ) {

				$host      = $_GET['host'];
				$domain    = ( isset( $_GET['domain'] ) ) ? $_GET['domain'] : false;
				$host_info = $vvv_dash->set_host_info( $host );
				$host_path = VVV_WEB_ROOT . '/' . $host_info['host'] . $host_info['path'];

				if ( $domain ) {
					$status = $vvv_dash->create_db_backup( $host );

					if ( $status ) {
						echo $status;
					}

					$cmd     = 'wp search-replace --url=' . $host . ' ' . $host . ' ' . $domain . ' --path=' . $host_path;
					$migrate = shell_exec( $cmd );

					if ( $migrate ) {

						$file_name = 'dumps/migrated-' . $domain . '_' . date( 'm-d-Y_g-i-s', time() ) . '.sql';
						$status    = $vvv_dash->create_db_backup( $host, $file_name );

						if ( $status ) {
							echo $status;
						}

						$migrate       = preg_split( "[\r|\n]", trim( $migrate ) );
						$m_table_array = array();
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
				?>
				<div class="row">
					<div class="col-sm-12"><p><a class="close" href="./">Close</a></p></div>
				</div>

				<div class="alert alert-warning" role="alert">
					<p class="">Migrating: <span class="bold italic"><?php echo $host; ?></span>
						<span class="bold pull-right">Warning this is a Beta feature!</span></p>
					<p>1. A backup will be created <br />2. Search and replace will happen on this host.
						<br />3. A new backup will be created marked as the migration</p>

					<form class="migrate-form form-inline" action="" method="get">
						<input type="hidden" name="host" value="<?php echo $host; ?>" />
						<input type="hidden" name="migrate" value="true" />
						<input class="domain" placeholder="The domain moving to" type="text" name="domain" value="<?php echo $domain; ?>" />
						<button class="btn btn-warning btn-sm" type="submit" name="migrate_db" value="true">Migrate</button>
					</form>
				</div>
				<?php
			}

			// Themes table
			if ( ! empty( $themes ) || isset( $_GET['themes'] ) ) {
				if ( isset( $_GET['host'] ) ) {
					?><h4>The theme list for
					<span class="red"><?php echo $_GET['host']; ?></span> <?php echo $close; ?></h4><?php

					// Create a New Theme Form base on _s
					$new_theme_form
						= '<form class="create-s-theme" action="" method="post">
								<span class="italic bold">Create a new theme based on <a href="http://underscores.me/" target="_blank">_s</a>  : </span>
								<input type="hidden" name="host" value="' . $_GET['host'] . '" />
								<input class="child-name" placeholder="Theme Name" type="text" name="theme_name" value="" />
								<input class="child-slug" placeholder="theme_slug" type="text" name="theme_slug" value="" />
								<button type="submit" class="btn btn-success btn-xs" name="create_s_theme" value="Create _s Theme">
								<i class="fa fa-paint-brush"></i> Create _s Theme
								</button>
							</form>
							';
					echo $new_theme_form;

					if ( isset( $_POST['create_s_theme'] ) ) {
						$themes_array = get_csv_names( $themes );
						$host_info    = $vvv_dash->set_host_info( $_POST['host'] );
						$host_path    = VVV_WEB_ROOT . '/' . $host_info['host'] . $host_info['path'];
						$slug         = strtolower( str_replace( ' ', '_', $_POST['theme_slug'] ) );

						// @ToDo allow this --force
						if ( in_array( $slug, $themes_array ) ) {
							echo vvv_dash_error( 'Error: That theme already exists!' );
						} else {
							$cmd       = 'wp scaffold _s ' . $slug . ' --theme_name="' . $_POST['theme_name'] . '" --path=' . $host_path .
							             ' --author="' . VVV_DASH_NEW_THEME_AUTHOR . '" --author_uri="' . VVV_DASH_NEW_THEME_AUTHOR_URI . '" --sassify --activate';
							$new_theme = shell_exec( $cmd );

							if ( $new_theme ) {
								$purge_status = $cache->purge( '-themes' );
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

						$host_info = $vvv_dash->set_host_info( $_POST['host'] );
						$host_path = VVV_WEB_ROOT . '/' . $host_info['host'] . $host_info['path'];
						$child     = strtolower( str_replace( ' ', '_', $_POST['child'] ) );

						if ( in_array( $child, $themes_array ) ) {
							echo vvv_dash_error( 'Error: That theme already exists!' );
						} else {
							$cmd       = 'wp scaffold child-theme ' . $child . ' --theme_name="' . $_POST['theme_name'] . '" --parent_theme=' . $_POST['item'] . ' --path=' . $host_path . ' --debug';
							$new_theme = shell_exec( $cmd );

							if ( $new_theme ) {
								$purge_status = $cache->purge( '-themes' );
								echo vvv_dash_notice( $purge_status . ' files were purged from cache!' );
								echo vvv_dash_notice( $new_theme );
							} else {
								echo vvv_dash_error( '<strong>Error:</strong> Something went wrong!' );
							}
						}
					}

					$host_info = $vvv_dash->set_host_info( $_GET['host'] );
					$themes    = $vvv_dash->get_themes_data( $host_info['host'], $host_info['path'] );

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

			vvv_dash_xdebug_status();


			include_once 'views/hosts-list.php'; ?>
		</div>
	</div>

<?php include_once 'views/php-error-logs.php';

// End dashboard.php