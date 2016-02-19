<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/16/15, 6:17 PM
 *
 * LICENSE: This is PRIVATE source code developed for clients.
 * It is in no way transferable and copy rights belong to Jeff Behnke @ ValidWebs.com
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * page-top.php
 */
$branch = shell_exec( 'cd ' . VVV_WEB_ROOT . '/default/dashboard  && git rev-parse --short HEAD && git rev-parse --abbrev-ref HEAD 2>&1' );
?>

	<div class="page-top">
		<h1 class="page-header"><i class="fa fa-tachometer"></i> VVV Dashboard

		</h1>

		<a class="btn btn-danger btn-sm get-backups" href="?page=backups"> <i class="fa fa-database"></i> Backups</a>
		<p><small>Version: <?php echo VVV_DASH_VERSION . ', Branch: ' . $branch ?></small></p>
	</div>
<?php
// End page-top.php