<?php

define( 'VVV_DASH_BASE', true );
define( 'VVV_WEB_ROOT', '/srv/www' );
define( 'VVV_DASH_VERSION', '0.1.4' );
define( 'VVV_DASH_VIEWS', VVV_WEB_ROOT . '/default/dashboard/views/' );

// Settings
$path = '../../';

// Make sure the user copied the dashboard-custom.php file over
if ( ! file_exists( '../dashboard-custom.php' ) ) {
	$msg = 'Please copy {VVV}/www/default/dashboard/dashboard-custom.php to {VVV}/www/default/dashboard-custom.php';
	die( $msg );
}

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
$status          = $vvv_dash->process_post();
$hosts           = $vvv_dash->get_hosts( $path );

if ( is_string( $hosts ) ) {
	$hosts = unserialize( $hosts );
}

$page = $vvv_dash->get_page();

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


include_once 'views/header.php';
include_once 'views/navbar.php';
?>
	<div class="container-fluid">

<?php include_once 'views/sidebar.php' ?>

	<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">

		<?php

		include_once 'views/notices.php';

		if ( isset($_REQUEST['page']) && file_exists( VVV_DASH_VIEWS . $_REQUEST['page'] ) ) {
			include_once 'views/' . $page . '.php';
		} else {
			if(isset($_REQUEST['page'])) {
				include_once 'views/404.php';
			} else {
				// default page
				include_once 'views/dashboard.php';
			}
		}
		?>

	</div>
<?php

include_once 'views/footer.php';