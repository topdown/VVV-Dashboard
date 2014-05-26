<?php

$path = '..';

/**
 * Create an array of the hosts from all of the VVV host files
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2014 ValidWebs.com
 *
 * Created:    5/23/14, 12:57 PM
 *
 * @param $path
 *
 * @return array
 */
function getHosts( $path ) {

	$array = array();
	$depth = 1;
	$ite   = new RecursiveDirectoryIterator( $path, RecursiveDirectoryIterator::SKIP_DOTS );
	$files = new RecursiveIteratorIterator( $ite );
	$files->setMaxDepth( $depth );

	// Loop through the file list and find what we want
	foreach ( $files as $name => $object ) {

		if ( strstr( $name, 'vvv-hosts' ) && !is_dir( 'vvv-hosts' ) ) {

			$lines = file( $name );

			// read through the lines in our host files
			foreach ( $lines as $num => $line ) {

				// skip comment lines
				if ( !strstr( $line, '#' ) && trim( $line ) != 'vvv.dev' ) {
					$array[] = trim( $line );
				}
			} // end foreach
		}
	} // end foreach
	return $array;
}

$hosts = getHosts( $path );

?>
<!DOCTYPE html>
<html>
<head>
	<title>Varying Vagrant Vagrants Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet"  type="text/css" href="style.css" />

</head>
<body>
<!-- Fixed navbar -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="./">Dashboard</a>
		</div>

			<ul class="nav navbar-nav">
				<li><a href="database-admin/" target="_blank">phpMyAdmin</a></li>
				<li><a href="memcached-admin/" target="_blank">phpMemcachedAdmin</a></li>
				<li><a href="opcache-status/opcache.php" target="_blank">Opcache Status</a></li>
				<li><a href="webgrind/" target="_blank">Webgrind</a></li>
				<li><a href="phpinfo/" target="_blank">PHP Info</a></li>
			</ul>

	</div>
</div>

<div class="container-fluid">

		<div class="col-sm-4 col-md-3 sidebar">

			<p class="sidebar-title">References &amp; Extras</p>
			<ul class="nav">
				<li><a target="_blank" href="https://github.com/aliso/vvv-site-wizard">VVV Site Wizard</a></li>
				<li>
					<a href="https://github.com/varying-vagrant-vagrants/vvv/" target="_blank">Varying Vagrant Vagrants</a>
				</li>
				<li>
					<a href="https://github.com/topdown/VVV-Dashboard" target="_blank">VVV Dashboard Repo</a>
				</li>
				<li>
					<a href="https://github.com/topdown/VVV-Dashboard/issues" target="_blank">VVV Dashboard Issues</a>
				</li>
			</ul>
		</div>
		<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">
			<h1 class="page-header">VVV Dashboard</h1>

			<div class="row">
				<div class="col-sm-8 col-md-7">

					<p><strong>Current Hosts</strong></p>
					<small>Note: To profile, <code>xdebug_on</code> must be set.</small>
					<ul class="list-unstyled sites">
						<?php
						foreach ( $hosts as $host ) {
							echo '<li class="row">
							<span class=" col-sm-7">' . $host . '</span>

							<div class=" col-sm-5">
							<a class="btn btn-primary btn-xs" href="http://' . $host . '/" target="_blank">Load</a>
							<a class="btn btn-success btn-xs" href="http://' . $host . '/?XDEBUG_PROFILE" target="_blank">Profile</a>
							</div>
							</li>' . "\n";
						} // end foreach
						unset( $host ); ?>
					</ul>

				</div>
				<div class="col-sm-8 col-md-4">
					<p><strong>Useful Commands</strong></p>
					<a href="https://github.com/varying-vagrant-vagrants/vvv/#now-what" target="_blank">Commands Link</a>
					<br />
					<ul class="list-unstyled">
					<li><code>vagrant up</code></li>
						<li><code>vagrant halt</code></li>
						<li><code>vagrant ssh</code></li>
						<li><code>vagrant suspend</code></li>
						<li><code>vagrant resume</code></li>
						<li><code>xdebug_on</code> <a href="https://github.com/Varying-Vagrant-Vagrants/VVV/wiki/Code-Debugging#turning-on-xdebug" target="_blank">Link</a></li>
				</ul>
				</div>
			</div>

			<h1>If Using <a href="https://github.com/aliso/vvv-site-wizard" target="_blank">VVV Site Wizard</a></h1>

			<p>This bash script makes it easy to spin up a new WordPress site using
				<a href="https://github.com/Varying-Vagrant-Vagrants/VVV">Varying Vagrant Vagrants</a>.</p>


			<h2>Usage</h2>

			<p>Type <code>vvv</code> in the command line to use. None of the options are required:
				if a required piece of information is not included in the original command,
				the wizard will prompt you for it.</p>

<pre><code>vvv -a list
vvv -a new -n mysite -d mysite.dev -v 3.9.1 -x
vvv -a delete mysite
</code></pre>

			<h3>Options</h3>

			<table class="table table-striped">
				<thead>
				<tr>
					<th>Option</th>
					<th>Name</th>
					<th>Description</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><code>-a</code></td>
					<td>action</td>
					<td>
						<code>new</code>/<code>create</code>/<code>make</code> runs the site creation wizard,
						<br /><code>rm</code>/<code>delete</code>/<code>teardown</code> runs the site teardown wizard,
						<br /><code>list</code> lists all current VVV sites
					</td>
				</tr>
				<tr>
					<td><code>-d</code></td>
					<td>domain</td>
					<td>Desired domain (e.g. mysite.dev)</td>
				</tr>
				<tr>
					<td><code>-f</code></td>
					<td>files only</td>
					<td>Do not provision Vagrant, just create the site directory and files</td>
				</tr>
				<tr>
					<td><code>-n</code></td>
					<td>site name</td>
					<td>Desired name for the site directory (e.g. mysite)</td>
				</tr>
				<tr>
					<td><code>-p</code></td>
					<td>path</td>
					<td>Path to VVV root (e.g. ~/vagrant-local)</td>
				</tr>
				<tr>
					<td><code>-v</code></td>
					<td>version</td>
					<td>Version of WordPress to install</td>
				</tr>
				<tr>
					<td><code>-x</code></td>
					<td>debug</td>
					<td>Turn on WP_DEBUG and WP_DEBUG_LOG</td>
				</tr>
				</tbody>
			</table>

			<p><strong>NOTE: </strong>This Dashboard project has no affiliation with Varying Vagrant Vagrants or any other components listed here.</p>

		</div>
</div>
</body>
</html>