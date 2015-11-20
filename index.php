<?php

define( 'VVV_DASH_BASE', true );
define( 'VVV_WEB_ROOT', '/srv/www' );
define( 'VVV_DASH_VERSION', '0.0.7' );

// Settings
$path = '../../';

include_once '../dashboard-custom.php';
include_once 'libs/vvv-dash-cache.php';
include_once 'libs/functions.php';

$plugins      = '';
$themes       = '';
$host         = '';
$purge_status = '';
$cache        = new vvv_dash_cache();

if ( ( $hosts = $cache->get( 'host-sites', VVV_DASH_HOSTS_TTL ) ) == false ) {

	$hosts  = get_hosts( $path );
	$status = $cache->set( 'host-sites', serialize( $hosts ) );
}

if ( is_string( $hosts ) ) {
	$hosts = unserialize( $hosts );
}


if ( isset( $_POST ) ) {

	if ( isset( $_POST['host'] ) && isset( $_POST['themes'] ) ) {

		$type = check_host_type( $_POST['host'] );

		if ( isset( $type['key'] ) ) {

			if ( isset( $type['path'] ) ) {
				$themes = get_themes( $type['key'], $type['path'] );
			} else {
				$themes = get_themes( $type['key'], '/' );
			}

		} else {
			$host   = strstr( $_POST['host'], '.', true );
			$themes = get_themes( $host, '/htdocs' );
		}

	}

	if ( isset( $_POST['host'] ) && isset( $_POST['plugins'] ) ) {

		$type = check_host_type( $_POST['host'] );

		if ( isset( $type['key'] ) ) {

			if ( isset( $type['path'] ) ) {
				$plugins = get_plugins( $type['key'], $type['path'] );
			} else {
				$plugins = get_plugins( $type['key'], '/' );
			}

		} else {
			$host    = strstr( $_POST['host'], '.', true );
			$plugins = get_plugins( $host, '/htdocs' );
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
}

include_once 'views/header.php';
include_once 'views/navbar.php';
?>
	<div class="container-fluid">

<?php include_once 'views/sidebar.php' ?>

	<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">

<?php purge_status( $purge_status ); ?>

	<h1 class="page-header">VVV Dashboard</h1>

	<div class="row">
		<div class="col-sm-12 hosts">
			<?php

			$close = '<a class="btn btn-primary btn-xs" href="./">Close</a>';
			if ( ! empty( $plugins ) ) {
				if ( isset( $_POST['host'] ) ) {
					?><h4>The plugin list for
					<span class="red"><?php echo $_POST['host']; ?></span> <?php echo $close; ?></h4><?php
				}
				echo format_table( $plugins );
			}
			if ( ! empty( $themes ) ) {
				if ( isset( $_POST['host'] ) ) {
					?><h4>The theme list for
					<span class="red"><?php echo $_POST['host']; ?></span> <?php echo $close; ?></h4><?php
				}
				echo format_table( $themes );
			}

			?>
			<p>
				<strong>Current Hosts = <?php echo isset( $hosts['site_count'] ) ? $hosts['site_count'] : ''; ?></strong>
			</p>
			<small>Note: To profile, <code>xdebug_on</code> must be set.</small>

			<p class="search-box">Live Search: <input type="text" id="text-search" /></p>

			<table class="sites table table-responsive table-striped table">
				<thead>
				<tr>
					<th>Debug Mode</th>
					<th>Sites</th>
					<th>Actions</th>
				</tr>
				</thead>
				<?php
				foreach ( $hosts as $key => $array ) {
					if ( 'site_count' != $key ) { ?>
						<tr>
							<?php if ( 'true' == $array['debug'] ) { ?>
								<td><span class="label label-success">Debug On</span></td>
							<?php } else { ?>
								<td><span class="label label-danger">Debug Off</span></td>
							<?php } ?>
							<td><?php echo $array['host']; ?></td>

							<td>
								<a class="btn btn-primary btn-xs" href="http://<?php echo $array['host']; ?>/" target="_blank">Visit Site</a>

								<?php if ( 'true' == $array['is_wp'] ) { ?>
									<a class="btn btn-warning btn-xs" href="http://<?php echo $array['host']; ?>/wp-admin" target="_blank">Admin/Login</a>
								<?php } ?>
								<a class="btn btn-success btn-xs" href="http://<?php echo $array['host']; ?>/?XDEBUG_PROFILE" target="_blank">Profiler</a>

								<form class="get-themes" action="" method="post">
									<input type="hidden" name="host" value="<?php echo $array['host']; ?>" />
									<input type="hidden" name="get_themes" value="true" />
									<input type="submit" class="btn btn-default btn-xs" name="themes" value="Themes" />
								</form>
								<form class="get-plugins" action="" method="post">
									<input type="hidden" name="host" value="<?php echo $array['host']; ?>" />
									<input type="hidden" name="get_plugins" value="true" />
									<input type="submit" class="btn btn-default btn-xs" name="plugins" value="Plugins" />
								</form>
							</td>
						</tr>
						<?php
					}
				}
				unset( $array ); ?>
			</table>
		</div>
	</div>

<?php
include_once 'views/commands-table.php';
include_once 'views/footer.php';