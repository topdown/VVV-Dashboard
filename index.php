<?php

define( 'VVV_DASH_BASE', true );
define( 'VVV_WEB_ROOT', '/srv/www' );
define( 'VVV_DASH_VERSION', '0.1.4' );

define( 'VVV_DASH_VIEWS', __DIR__ . '/views' );

// Settings
$path = '../../';

// Make sure the user copied the dashboard-custom.php file over
if ( ! file_exists( '../dashboard-custom.php' ) ) {
	$msg = 'Please copy {VVV}/www/default/dashboard/dashboard-custom.php to {VVV}/www/default/dashboard-custom.php';
	die( $msg );
}

include_once '../dashboard-custom.php';
include_once 'libs/vvv-dash-cache.php';

// The new files for commands and actions
include_once 'libs/commands.php';
include_once 'libs/commands/dashboard.php';
include_once 'libs/commands/host.php';
include_once 'libs/commands/database.php';
include_once 'libs/commands/plugin.php';
include_once 'libs/commands/theme.php';
include_once 'libs/commands/favs.php';


// These will get cleanup a lot most moved to the commands
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
$debug_log_path  = false;
$cache           = new vvv_dash_cache();
$vvv_dash        = new vvv_dashboard();
$status          = $vvv_dash->process_post();
$hosts           = $vvv_dash->get_hosts( $path );

if ( is_string( $hosts ) ) {
	$hosts = unserialize( $hosts );
}

$page = $vvv_dash->get_page();

if ( isset( $_GET ) ) {

	$host_commands    = new \vvv_dash\commands\host();
	$theme_commands    = new \vvv_dash\commands\theme();
	$plugin_commands   = new \vvv_dash\commands\plugin();
	$database_commands = new \vvv_dash\commands\database();
	$themes            = $theme_commands->get_themes( $_GET );
	$plugins           = $plugin_commands->get_plugins( $_GET );
	

}

include_once 'views/partials/header.php';
include_once 'views/partials/navbar.php';
?>
	<div class="container-fluid">

<?php include_once 'views/partials/sidebar.php' ?>

	<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">

		<?php

		include_once 'views/partials/page-top.php';

		include_once 'views/partials/notices.php';

		if ( isset( $_REQUEST['page'] ) && file_exists( VVV_DASH_VIEWS . $_REQUEST['page'] . '.php' ) ) {
			include_once 'views/' . $page . '.php';
		} else {
			if ( isset( $_REQUEST['page'] ) ) {
				include_once 'views/404.php';
			} else {
				// default page
				include_once 'views/dashboard.php';
			}
		}
		?>

	</div>
<?php

include_once 'views/partials/footer.php';