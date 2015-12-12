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

			<ul class="nav navbar-nav">
				<li><a href="/database-admin/" target="_blank">phpMyAdmin</a></li>
				<li><a href="/memcached-admin/" target="_blank">phpMemcachedAdmin</a></li>
				<li><a href="/opcache-status/opcache.php" target="_blank">Opcache Status</a></li>
				<li><a href="/webgrind/" target="_blank">Webgrind</a></li>
				<li><a href="/phpinfo/" target="_blank">PHP Info</a></li>
				<li><a href="//vvv.dev:1080/" target="_blank">Mailcatcher</a></li>
			</ul>
			<p class="pull-right">Help grow the feature list: <span class="badge-paypal"><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KEUN2SQ2VRW7A" title="Donate to this project using Paypal"><img src="https://img.shields.io/badge/paypal-donate-yellow.svg" alt="PayPal donate button" /></a></span>
				<a class="github-link" href="https://github.com/topdown/VVV-Dashboard" target="_blank"><i class="fa fa-github fa-2x fa-inverse"></i></a>
			</p>

		</div>
	</div>
<?php
// End navbar.php