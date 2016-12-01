<?php

// Need to do some load testing
global $timestart;
$time      = microtime();
$time      = explode( ' ', $time );
$timestart = $time[1] + $time[0];

define( 'VVV_DASH_BASE', true );
define( 'VVV_WEB_ROOT', '/srv/www' );
define( 'VVV_DASH_ROOT', __DIR__);
define( 'VVV_DASH_VERSION', '0.1.9' );
define( 'VVV_DASH_VIEWS', __DIR__ . '/views' );
define('VVV_DASH_HOSTS_DEBUG', false);

// Settings
$path = '../../';

// Make sure the user copied the dashboard-custom.php file over
if ( ! file_exists( '../dashboard-custom.php' ) ) {
	$msg = 'Please copy {VVV}/www/default/dashboard/dashboard-custom.php to {VVV}/www/default/dashboard-custom.php';
	die( $msg );
}

include_once '../dashboard-custom.php';
include_once 'vvv_dash/cache.php';
// Cache instance
$cache = new \vvv_dash\cache();

// Host farm :P Setup to manage different kinds of vvv host setups with hopefully less work
include_once 'vvv_dash/hosts_container.php';
include_once 'vvv_dash/hosts.php';
include_once 'vvv_dash/hosts/defaults.php';
include_once 'vvv_dash/hosts/standard_wp.php';
include_once 'vvv_dash/hosts/wp_starter.php';
//include_once 'vvv_dash/hosts/bedrock.php';

$host_object = new \vvv_dash\hosts();
$standard    = new \vvv_dash\hosts\standard_wp();
$wp_starter  = new \vvv_dash\hosts\wp_starter();
$defaults    = new \vvv_dash\hosts\defaults();

if ( ( $host_info = $cache->get( 'host-sites', VVV_DASH_HOSTS_TTL ) ) == false ) {

	$standard->load_hosts();
	$defaults->load_hosts();
	$wp_starter->load_hosts();
	$host_info = \vvv_dash\hosts_container::get_host_list();

	$status = $cache->set( 'host-sites', serialize( $host_info ) );
}

if ( ! is_array( $host_info ) ) {
	$host_info = unserialize( $host_info );
}

// Playing around with random sorting :)
//$host_info = array_values($host_info);
//shuffle( $host_info );

// The new files for commands and actions
//include_once 'vvv_dash/commands.php';
include_once 'vvv_dash/commands/host.php';
include_once 'vvv_dash/commands/database.php';
include_once 'vvv_dash/commands/plugin.php';
include_once 'vvv_dash/commands/theme.php';
include_once 'vvv_dash/commands/favs.php';


// These will get cleanup a lot most moved to the commands
include_once 'vvv_dash/dashboard.php';
include_once 'vvv_dash/functions.php';

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
$vvv_dash        = new \vvv_dash\dashboard();
$host_commands   = new \vvv_dash\commands\host();
$status          = $vvv_dash->process_post();
$page            = $vvv_dash->get_page();

if ( isset( $_GET ) ) {
	$current_host      = ( isset( $_GET['host'] ) ) ? $_GET['host'] : '';
	$theme_commands    = new \vvv_dash\commands\theme( $current_host );
	$plugin_commands   = new \vvv_dash\commands\plugin( $current_host );
	$database_commands = new \vvv_dash\commands\database( $current_host );
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

		if ( isset( $_REQUEST['page'] ) && file_exists( VVV_DASH_VIEWS . '/' . $_REQUEST['page'] . '.php' ) ) {
			include_once 'views/' . $page . '.php';
		} else {
			if ( isset( $_REQUEST['page'] ) ) {
				include_once 'views/404.php';
			} else {
				// default page
				include_once 'views/dashboard.php';
			}
		}

		if(VVV_DASH_HOSTS_DEBUG) {
			echo '<pre style="text-align: left;">';
			print_r(\vvv_dash\hosts_container::get_host_list());
			echo '</pre>';
		}
		?>

	</div>
<?php

include_once 'views/partials/footer.php';