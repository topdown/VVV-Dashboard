<?php

define( 'VVV_DASH_BASE', true );
define( 'VVV_WEB_ROOT', '/srv/www' );
define('VVV_DASH_VERSION', '0.0.6');

// Settings
$path = '../../';

include_once '../dashboard-custom.php';
include_once 'libs/vvv-dash-cache.php';
include_once 'libs/functions.php';

$plugins = '';
$themes  = '';
$host    = '';
$cache   = new vvv_dash_cache();

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
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Varying Vagrant Vagrants Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style.css?ver=5" />
	<script type="text/JavaScript" src="bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="bower_components/js-cookie/src/js.cookie.js"></script>
	<script type="text/javascript" src="src/js/scripts.js"></script>
</head>
<body>
<div id="wrapper">
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<a href="#menu-toggle" class="btn btn-success btn-xs" id="menu-toggle">Sidebar</a>
				<a class="navbar-brand" href="./">Dashboard</a>
			</div>

			<ul class="nav navbar-nav">
				<li><a href="/database-admin/" target="_blank">phpMyAdmin</a></li>
				<li><a href="/memcached-admin/" target="_blank">phpMemcachedAdmin</a></li>
				<li><a href="/opcache-status/opcache.php" target="_blank">Opcache Status</a></li>
				<li><a href="/webgrind/" target="_blank">Webgrind</a></li>
				<li><a href="/phpinfo/" target="_blank">PHP Info</a></li>
			</ul>
		</div>
	</div>

	<div class="container-fluid">
		<div class="col-sm-4 col-md-3 sidebar">

			<p class="sidebar-title">Quick Info</p>
			<ul class="nav">
				<li>Public IP: <?php echo $_SERVER['SERVER_ADDR']; ?>     </li>
				<li>Main Address: <?php echo $_SERVER['SERVER_NAME']; ?>     </li>
				<li>Server: <?php echo $_SERVER['SERVER_SOFTWARE']; ?> </li>
				<li>Document Root: <?php echo str_replace( '/default', '', $_SERVER['DOCUMENT_ROOT'] ); ?>   </li>
				<li>HTTP Port: <?php echo $_SERVER['SERVER_PORT']; ?>     </li>
				<li>See PHP Info for more details.</li>
			</ul>

			<p class="sidebar-title">Useful Commands</p>
			<ul class="nav">
				<li>
					<a href="https://github.com/varying-vagrant-vagrants/vvv/#now-what" target="_blank">Vagrant Commands</a>
				</li>
				<li><code>vagrant up</code></li>
				<li><code>vagrant halt</code></li>
				<li><code>vagrant ssh</code></li>
				<li><code>vagrant suspend</code></li>
				<li><code>vagrant resume</code></li>
				<li><code>xdebug_on</code>
					<a href="https://github.com/Varying-Vagrant-Vagrants/VVV/wiki/Code-Debugging#turning-on-xdebug" target="_blank">xDebug Instructions</a>
				</li>
			</ul>


			<p class="sidebar-title">References &amp; Extras</p>
			<ul class="nav">
				<li><a target="_blank" href="https://github.com/bradp/vv">Variable VVV</a></li>
				<li>
					<a href="https://github.com/varying-vagrant-vagrants/vvv/" target="_blank">Varying Vagrant Vagrants</a>
				</li>
				<li><a href="https://github.com/topdown/VVV-Dashboard" target="_blank">VVV Dashboard Repo</a></li>
				<li><a href="https://github.com/topdown/VVV-Dashboard/issues" target="_blank">VVV Dashboard Issues</a>
				</li>
				<li>
					<a href="https://github.com/aubreypwd/wordpress-themereview-vvv" target="_blank">VVV WordPress ThemeReview</a>
				</li>
			</ul>
		</div>
		<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">
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

					<p class="search-box">Live Search: <input type="text" id="text-search" />
						<!--<input id="search" type="button" value="Search" />
						<input id="back" type="button" value="Search Up" /> &nbsp;
						<small>Enter, Up and Down keys are bound.</small>-->
					</p>

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

			<h1>To easily spin up new WordPress sites</h1>

			<p>
				Install and use <a target="_blank" href="https://github.com/bradp/vv">Variable VVV (newest)</a><br />
				<a target="_blank" href="https://github.com/bradp/vv#vv-options">VV Options</a><br />
				<a target="_blank" href="https://github.com/bradp/vv#options-for-site-creation">VV Site Create Options</a>

			</p>

			<h2>Variable VVV Commands</h2>

			<table class="table table-responsive table-bordered table-striped">
				<thead>
				<tr>
					<th>Command</th>
					<th>Description</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>
						list or --list or -l
					</td>
					<td>
						List all VVV sites
					</td>
				</tr>
				<tr>
					<td>
						create or --create or -c
					</td>
					<td>
						Create a new site
					</td>
				</tr>
				<tr>
					<td>
						remove or --remove or -r
					</td>
					<td>
						Remove a site
					</td>
				</tr>
				<tr>
					<td>
						deployment-create or --deployment-create
					</td>
					<td>
						Set up deployment for a site
					</td>
				</tr>
				<tr>
					<td>
						deployment-remove or --deployment-remove
					</td>
					<td>
						Remove deployment for a site
					</td>
				</tr>
				<tr>
					<td>
						deployment-config or --deployment-config
					</td>
					<td>
						Manually edit deployment configuration
					</td>
				</tr>
				<tr>
					<td>
						blueprint-init or --blueprint-init
					</td>
					<td>
						Initialize blueprint file
					</td>
				</tr>
				<tr>
					<td>
						vagrant v --vagrant -v
					</td>
					<td>
						Pass vagrant command through to VVV
					</td>
				</tr>

				</tbody>
			</table>

			<p>This bash script makes it easy to spin up a new WordPress site using
				<a href="https://github.com/Varying-Vagrant-Vagrants/VVV">Varying Vagrant Vagrants</a>.</p>

			<p>
				<strong>NOTE: </strong>This Dashboard project has no affiliation with Varying Vagrant Vagrants or any other components listed here.
			</p>

			<p>
				<small>VVV Dashboard Version: <?php echo VVV_DASH_VERSION; ?></small>
			</p>
		</div>
	</div>
</div>
</body>
</html>