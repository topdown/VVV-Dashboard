<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/20/15, 12:26 AM
 *
 * LICENSE: This is PRIVATE source code developed for clients.
 * It is in no way transferable and copy rights belong to Jeff Behnke @ ValidWebs.com
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * navbar.php
 */
?>
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
				<li><a href="//vvv.dev:1080/" target="_blank">Mailcatcher</a></li>
				<li><a href="new-site.php">New Site</a></li>
			</ul>
		</div>
	</div>
<?php
// End navbar.php