<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/16/15, 6:05 PM
 *
 * LICENSE: MIT
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * backups.php
 */

$backups_table = get_backups_table();

$close = '<a class="close" href="./">Close</a>';

if ( ! empty( $backups_table ) ) {
	?><h4 class="title">Backups List
	<span class="small"> Path: {VVV}/default/dashboard/dumps/ </span> <?php echo $close; ?></h4><?php
	echo $backups_table;
}

// End backups.php