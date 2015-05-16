<?php


function redirect_to_vvv_dash( $url, $status_code = 301 ) {
	header( 'Location: ' . $url, true, $status_code );
	die();
}

redirect_to_vvv_dash( '/dashboard/index.php', 302 );