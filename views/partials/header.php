<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/20/15, 12:22 AM
 *
 * LICENSE:
 *
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
		<?php if ( file_exists( 'custom.css' ) ) { ?>
			<link rel="stylesheet" type="text/css" href="custom.css" />
		<?php } ?>
		<link rel="stylesheet" type="text/css" href="bower_components/fontawesome/css/font-awesome.min.css?ver=<?php echo VVV_DASH_VERSION; ?>">

		<link rel="apple-touch-icon" sizes="57x57" href="src/icons/apple-icon-57x57.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="apple-touch-icon" sizes="60x60" href="src/icons/apple-icon-60x60.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="apple-touch-icon" sizes="72x72" href="src/icons/apple-icon-72x72.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="apple-touch-icon" sizes="76x76" href="src/icons/apple-icon-76x76.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="apple-touch-icon" sizes="114x114" href="src/icons/apple-icon-114x114.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="apple-touch-icon" sizes="120x120" href="src/icons/apple-icon-120x120.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="apple-touch-icon" sizes="144x144" href="src/icons/apple-icon-144x144.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="apple-touch-icon" sizes="152x152" href="src/icons/apple-icon-152x152.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="apple-touch-icon" sizes="180x180" href="src/icons/apple-icon-180x180.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="icon" type="image/png" sizes="192x192" href="src/icons/android-icon-192x192.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="src/icons/favicon-32x32.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="icon" type="image/png" sizes="96x96" href="src/icons/favicon-96x96.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="src/icons/favicon-16x16.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<link rel="manifest" href="src/icons/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="src/icons/ms-icon-144x144.png?ver=<?php echo VVV_DASH_VERSION; ?>">
		<meta name="theme-color" content="#ffffff">

		<script type="text/JavaScript" src="bower_components/jquery/dist/jquery.min.js"></script>
		<script type="text/JavaScript" src="src/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="bower_components/js-cookie/src/js.cookie.js"></script>
		<script type="text/javascript" src="bower_components/bootstrap-sass/vendor/assets/javascripts/bootstrap/alert.js"></script>
		<script type="text/javascript" src="bower_components/bootstrap-sass/vendor/assets/javascripts/bootstrap/tooltip.js"></script>
		<script type="text/javascript" src="bower_components/bootstrap-sass/vendor/assets/javascripts/bootstrap/dropdown.js"></script>
		<script type="text/javascript" src="src/js/scripts.js?ver=<?php echo VVV_DASH_VERSION; ?>"></script>

		<?php if ( file_exists( 'src/js/custom.js' ) ) { ?>
			<script type="text/javascript" src="src/js/custom.js"></script>
		<?php } ?>
	</head>
	<body>
	<div id="wrapper">
<?php
// End header.php