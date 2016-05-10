<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 1:22 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * favorite_plugins.php
 */
$host = isset($_GET['host']) ? $_GET['host']: die('No Host given');
?>
	<form class="" action="" method="post">
		<p><span class="bold">Install a favorite plugin on this host.</span><br />
			<span class="italic bold red">NOTE: the more plugins you check at one time the longer it takes.</span>
		</p>
		<input type="hidden" name="host" value="<?php echo $host; ?>" /> <?php echo $checkboxes; ?>
		<button type="submit" class="btn btn-success btn-xs" name="install_fav_plugin" value="Install Plugin">
			<i class="fa fa-gears"></i> Install Plugin
		</button>
	</form><br />
<?php
// End favorite_plugins.php