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

$backups = new \vvv_dash\commands\database();
$backups_table = $backups->get_backups_table();

$close = '<a class="close" href="./">Dashboard</a>';

if ( ! empty( $backups_table ) ) {
	?><h4 class="title">All Backups
	<span class="small"> Path: {VVV}/default/dashboard/dumps/ </span> <?php echo $close; ?></h4>

	<div id="search_container" class="input-group search-box">
		<span class="input-group-addon"> <i class="fa fa-search"></i> </span>
		<input type="text" class="form-control search-input" id="backups-search" placeholder="Live Search..." />
		<span class="input-group-addon"> Hosts <span class="badge"><?php echo count( $host_info ); ?></span> </span>
	</div>

	<?php
	echo $backups_table;
}

// End backups.php