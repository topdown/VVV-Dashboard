<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/11/16, 6:45 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * database_reset.php
 */
?>
<div class="alert alert-danger alert-dismissible" role="alert">
	<p><strong>Warning</strong><br> Are you sure you want to reset the database.</p>
	<p>It will delete everything in the database and reinstall WordPress database.</p>
	<p>	<a href="./?page=tools&host=<?php echo $current_host; ?>&action=db_reset&confirm=true" class="btn btn-danger btn-xs">Confirm</a>
</p>
</div>

