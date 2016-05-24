<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/20/15, 12:27 AM
 *
 * LICENSE:
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * sidebar.php
 */
?>
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

		<p class="sidebar-title">Purge Cache</p>

		<form class="get-plugins" action="" method="post">
			<input type="submit" class="btn btn-success btn-xs" name="purge_hosts" value="Purge Hosts" />
		</form>
		<form class="get-plugins" action="" method="post">
			<input type="submit" class="btn btn-danger btn-xs" name="purge_plugins" value="Purge Plugins" />
		</form>
		<form class="get-plugins" action="" method="post">
			<input type="submit" class="btn btn-primary btn-xs" name="purge_themes" value="Purge Themes" />
		</form>

		<br /> <br />

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
<?php
// End sidebar.php