<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/7/15, 4:03 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * notices.php
 */

//if($purge_status) {
//	purge_status( $purge_status );
//}

if ( version_compare( VVV_DASH_VERSION, version_check(), '<' ) ) {
	?>
	<div class="alert alert-danger alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<p>A new version of <em> VVV Dashboard</em> is available for the branch you are on <code><?php echo $branch; ?></code>.
		<br />Your current version: <?php echo VVV_DASH_VERSION ?>
		<br /> <strong> New version: <?php echo version_check(); ?></strong>
		<br />You can update with a <code>git pull</code>.
	</p>
	</div><?php

	echo vvv_dash_notice( '<h3>New Features in the new release.</h3>' . vvv_dash_new_features() );
}

if ( $status ) echo $status;

// End notices.php
