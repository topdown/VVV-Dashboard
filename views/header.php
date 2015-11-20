<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/20/15, 12:22 AM
 *
 * LICENSE: This is PRIVATE source code developed for clients.
 * It is in no way transferable and copy rights belong to Jeff Behnke @ ValidWebs.com
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * header.php
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Varying Vagrant Vagrants Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style.css?ver=<?php echo VVV_DASH_VERSION; ?>" />
	<?php if(file_exists('custom.css')) {
		?><link rel="stylesheet" type="text/css" href="custom.css" /><?php
	} ?>
	<script type="text/JavaScript" src="bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="bower_components/js-cookie/src/js.cookie.js"></script>
	<script type="text/javascript" src="bower_components/bootstrap-sass/vendor/assets/javascripts/bootstrap/alert.js"></script>
	<script type="text/javascript" src="src/js/scripts.js?ver=<?php echo VVV_DASH_VERSION; ?>"></script>
</head>
<body>
<div id="wrapper">
<?php
// End header.php