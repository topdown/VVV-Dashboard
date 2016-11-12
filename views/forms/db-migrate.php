<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 1:32 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * migrate.php
 */

$host = isset($_GET['host']) ? $_GET['host']: false;
$domain = '';
if($host) {
	?>
	<div class="alert alert-warning" role="alert">
		<p class="">Migrating: <span class="bold italic"><?php echo $host; ?></span>
			<span class="bold pull-right">Warning this is a Beta feature!</span></p>
		<p>1. A backup will be created <br />2. Search and replace will happen on this host.
			<br />3. A new backup will be created marked as the migration</p>

		<!-- @Todo remove this get form if possible -->
		<form class="migrate-form form-inline" action="" method="get">
			<input type="hidden" name="page" value="tools" />
			<input type="hidden" name="action" value="db_migrate" />
			<input type="hidden" name="host" value="<?php echo $host; ?>" />
			<input type="hidden" name="migrate" value="true" />
			<input class="domain" placeholder="The domain moving to" type="text" name="domain" value="<?php echo $domain; ?>" />
			<button class="btn btn-warning btn-sm" type="submit" name="migrate_db" value="true">Migrate</button>
		</form>
	</div>
	<?php
}



// End migrate.php