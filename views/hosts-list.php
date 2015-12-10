<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/7/15, 3:48 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * hosts-table.php
 */
?>
<p class="red italic"><span class="bold">NOTE</span>: After creating or changing a host/site purge the Host Cache.</p>
	<div id="search_container" class="input-group search-box">
		<span class="input-group-addon"> <i class="fa fa-search"></i> </span>
		<input type="text" class="form-control search-input" id="text-search" placeholder="Live Search..." />
		<span class="input-group-addon"> Hosts
			<span class="badge"><?php echo isset( $hosts['site_count'] ) ? $hosts['site_count'] : ''; ?></span> </span>
	</div>

	<table class="sites table table-responsive table-striped table-bordered table-hover">
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
							<td><span class="label label-danger">Debug Off <i class="fa fa-times-circle-o"></i></span>
							</td>
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

<!--							<form class="get-plugins" action="" method="post">-->
<!--								<input type="hidden" name="host" value="--><?php //echo $array['host']; ?><!--" />-->
<!--								<input type="submit" class="btn btn-success btn-xs" name="install_dev_plugins" value="Dev Plugins" />-->
<!--							</form>-->

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
<?php

// End hosts-table.php