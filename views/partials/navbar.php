<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/20/15, 12:26 AM
 *
 * LICENSE:
 *
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
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							VVV Tools
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="/database-admin/" target="_blank">phpMyAdmin</a></li>
							<li><a href="/memcached-admin/" target="_blank">phpMemcachedAdmin</a></li>
							<li><a href="/opcache-status/opcache.php" target="_blank">Opcache Status</a></li>
							<li><a href="/webgrind/" target="_blank">Webgrind</a></li>
							<li><a href="/phpinfo/" target="_blank">PHP Info</a></li>
							<li><a href="//vvv.dev:1080/" target="_blank">Mailcatcher</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							Dashboard Pages
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="/dashboard/?page=about">About</a></li>
							<li><a href="/dashboard/?page=commands">VV &amp; VVV Commands</a></li>
							<li><a href="/dashboard/?page=backups">Backups</a></li>
						</ul>
					</li>
				</ul>
				<p class="pull-right">
					<a title="Join the party on Gitter Chat" class="gitter" href="https://gitter.im/topdown/VVV-Dashboard" target="_blank"><i class="fa fa-comments-o  fa-2x fa-inverse"></i></a>
					<a title="VVV Dashboard on GitHub" class="github-link" href="https://github.com/topdown/VVV-Dashboard" target="_blank"><i class="fa fa-github fa-2x fa-inverse"></i></a>
					<a title="VVV Dashboard Documentation" class="github-link" href="https://github.com/topdown/VVV-Dashboard/wiki" target="_blank"><i class="fa fa-book  fa-2x fa-inverse"></i></a>
				</p>
			</div><!-- /.navbar-collapse -->
		</div>
	</div>
<?php
// End navbar.php