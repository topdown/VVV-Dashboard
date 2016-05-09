<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/7/16, 2:45 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * hosts-list2.php
 */

$host_object      = new \vvv_dash\hosts();

$standard   = new \vvv_dash\hosts\standard_wp();
$standard->load_hosts();

$wp_starter = new \vvv_dash\hosts\wp_starter();
$wp_starter->load_hosts();

$defaults   = new \vvv_dash\hosts\defaults();
$defaults->load_hosts();

$host_info = \vvv_dash\hosts_container::get_host_list();

?>
	<p class="red italic"><span class="bold">NOTE</span>: After creating or changing a host/site purge the Host Cache.
	</p>
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
		//		$files  = glob( VVV_WEB_ROOT . "/*" );
		//
		////if ( ( $host_info = $cache->get( 'host-sites-dev', VVV_DASH_HOSTS_TTL ) ) == false ) {
		//
		//	//$files     = unserialize( $files );
		//	$host_info = array();
		//	//print each file name
		//	foreach ( $files as $key => $file ) {
		//		//check to see if the file is a folder/directory
		//		if ( is_dir( $file ) ) {
		//			$hosts    = new \vvv_dash\hosts();
		//			$hostname = str_replace( '/srv/www/', '', $file );
		//			$hosts->set_host( $hostname );
		//			$host_data = $hosts->get_host_info();
		//
		//
		//			if ( $host_data['is_wp_site'] == 'true' ) {
		//				$host_info[] = $host_data;
		//
		//			}
		//
		//		}
		//	}
		//
		//	//$status = $cache->set( 'host-sites-dev', serialize( $host_info ) );
		////}

		foreach ( $host_info as $key => $host ) {
			?>
			<tr>
				<?php if ( isset( $host['config_settings']['WP_DEBUG'] ) && $host['config_settings']['WP_DEBUG'] == 'true' ) { ?>
					<td><span class="label label-success">Debug On <i class="fa fa-check-circle-o"></i></span>
					</td>
				<?php } else {
					if ( $host['wp_is_installed'] == 'true' ) {
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

				<td class="host"><?php echo $host['domain']; ?></td>
				<td><?php
					if ( isset( $host['wp_version'] ) ) {
						echo $host['wp_version'];
					} else {
						echo 'N/A';
					}
					?></td>

				<td>
					<a class="btn btn-primary btn-xs" href="http://<?php echo $host['domain']; ?>/" target="_blank">
						<i class="fa fa-external-link"></i> Visit </a>

					<?php if ( $host['wp_is_installed'] == 'true' ) { ?>
						<a class="btn btn-warning btn-xs" href="http://<?php echo $host['domain']; ?>/wp-admin" target="_blank">
							<i class="fa fa-wordpress"></i> Admin </a>
					<?php } ?>
					<a class="btn btn-success btn-xs" href="http://<?php echo $host['domain']; ?>/?XDEBUG_PROFILE" target="_blank">
						<i class="fa fa-search-plus"></i> Profiler </a>

					<?php if ( $host['wp_is_installed'] == 'true' ) { ?>
						<a href="./?host=<?php echo $host['domain']; ?>&get_themes=true" class="btn btn-default btn-xs">
							<i class="fa fa-paint-brush"></i><span> Themes</span> </a>

						<a href="./?host=<?php echo $host['domain']; ?>&get_plugins=true" class="btn btn-default btn-xs">
							<i class="fa fa-puzzle-piece"></i><span> Plugins</span> </a>

					<?php }

					if ( $host['wp_is_installed'] == 'true' ) {
						?>
						<form class="backup form-inline" action="" method="post">
							<input type="hidden" name="host" value="<?php echo $host['domain']; ?>" />
							<button title="Backup the database" type="submit" class="btn btn-info btn-xs" name="backup" value="Backup DB" data-toggle="tooltip" data-placement="top">
								<i class="fa fa-database"></i><span> Backup DB</span>
							</button>
						</form>

						<a href="./?host=<?php echo $host['domain']; ?>&migrate=true" class="btn btn-warning btn-xs">
							<i class="fa fa-database"></i><span> Migrate</span></a>

						<?php
					}

					if ( file_exists( $host['debug_log'] ) ) { ?>
						<a href="./?host=<?php echo $host['domain']; ?>&debug_log=true" class="btn btn-danger btn-xs">
							<i class="fa fa-exclamation-circle"></i><span> Errors</span></a>
					<?php } ?>
				</td>
			</tr>
			<?php
		}
		unset( $host ); ?>
	</table>

<?php
// End hosts-list2.php