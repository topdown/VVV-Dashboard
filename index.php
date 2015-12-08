<?php

define( 'VVV_DASH_BASE', true );
define( 'VVV_WEB_ROOT', '/srv/www' );
define( 'VVV_DASH_VERSION', '0.1.3' );

// Settings
$path = '../../';
include_once '../dashboard-custom.php';
include_once 'libs/vvv-dash-cache.php';
include_once 'libs/vvv-dash-hosts.php';
include_once 'libs/vvv-dashboard.php';
include_once 'libs/functions.php';

// Make sure everything is ready
vvv_dash_prep();

$plugins         = '';
$themes          = '';
$backups_table   = '';
$host            = '';
$debug_log       = '';
$debug_log_lines = '';
$debug_log_path  = '';
$cache           = new vvv_dash_cache();
$vvv_dash        = new vvv_dashboard();
$hosts           = $vvv_dash->get_hosts( $path );

if ( is_string( $hosts ) ) {
	$hosts = unserialize( $hosts );
}

if ( isset( $_GET ) ) {
	$themes          = $vvv_dash->get_themes( $_GET );
	$plugins         = $vvv_dash->get_plugins( $_GET );
	$wp_debug_log    = $vvv_dash->get_wp_debug_log( $_GET );
	$debug_log_lines = ( isset( $wp_debug_log['lines'] ) ) ? $wp_debug_log['lines'] : false;
	$debug_log_path  = ( isset( $wp_debug_log['path'] ) ) ? $wp_debug_log['path'] : false;
}

if ( isset( $_GET['get_backups'] ) && 'Backups' == $_GET['get_backups'] ) {
	$backups_table = get_backups_table();
}

$status = $vvv_dash->process_post();

include_once 'views/header.php';
include_once 'views/navbar.php';
?>
	<div class="container-fluid">

<?php include_once 'views/sidebar.php' ?>

	<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">

		<?php

		include_once 'views/notices.php';

		include_once 'views/dashboard.php';

		include_once 'views/commands-table.php';
		?>

	</div>
<?php

include_once 'views/footer.php';