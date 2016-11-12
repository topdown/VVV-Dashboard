<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/11/16, 2:44 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * database.php
 */
$close = '<a class="close" href="./">Dashboard</a>';

if ( isset( $_GET['host'] ) ) {
	$current_host = $_GET['host'];
	?><h3 class="title">Site Management for
	<span class="bold red"><?php echo $current_host; ?></span> <?php echo $close; ?></h3>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8 col-md-9 main">
				<h4>Database Tools</h4>

				<a href="./?page=tools&host=<?php echo $current_host; ?>&action=db_backup" class="btn btn-success">
					<i class="fa fa-database"></i><span> Backup</span></a>
				<a href="./?page=tools&host=<?php echo $current_host; ?>&action=db_check" class="btn btn-primary">
					<i class="fa fa-database"></i><span> Check</span></a>
				<a href="./?page=tools&host=<?php echo $current_host; ?>&action=db_repair" class="btn btn-primary">
					<i class="fa fa-database"></i><span> Repair</span></a>
				<a href="./?page=tools&host=<?php echo $current_host; ?>&action=db_optimize" class="btn btn-primary">
					<i class="fa fa-database"></i><span> Optimize</span></a>
				<a href="./?page=tools&host=<?php echo $current_host; ?>&action=db_tables" class="btn btn-primary">
					<i class="fa fa-database"></i><span> List Tables</span></a>
				<a href="./?page=tools&host=<?php echo $current_host; ?>&action=db_migrate" class="btn btn-warning">
					<i class="fa fa-database"></i><span> Migrate</span></a>
				<a href="./?page=tools&host=<?php echo $current_host; ?>&action=db_reset" class="btn btn-danger">
					<i class="fa fa-database"></i><span> Reset</span></a>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8 col-md-9 main">
				<?php

				$database_commands = new \vvv_dash\commands\database( $current_host );

				if ( isset( $_GET['action'] ) ) {
					$action = $_GET['action'];

					switch ( $action ) {

						case 'db_reset' :
							echo '<h4>Database Reset</h4>';
							if ( ! isset( $_GET['confirm'] ) ) {
								include_once 'partials/database-reset.php';
							} else {
								$status = $database_commands->database_reset();

								$status = explode( "\n", $status );
								$status = array_filter( $status );

								foreach ( $status as $info ) {
									echo '<div class="alert alert-success alert-dismissible" role="alert"><p>' . $info . '</p></div>';
								} // end foreach
								unset( $info );

							}
							break;

						case 'db_backup' :
							echo '<h4>Backup Database</h4>';

							$command = new \vvv_dash\commands\database( $current_host );
							$status  = $command->create_db_backup( $current_host );

							if ( $status ) {
								echo '<p>' . $status . '</p>';
							}

							break;

						case 'db_migrate' :
							echo '<h4>Migrate Database</h4>';

							// Mostly Migrate stuff, come back to this
							$database_commands->migrate();
							// Migration Form
							// @var $host
							// @var $domain
							if ( ! isset( $_GET['migrate'] ) && isset( $_GET['host'] ) ) {
								include_once 'forms/db-migrate.php';
							}

							break;


						case 'db_check' :
							echo '<h4>Check Database</h4>';
							$status = $database_commands->database_check();

							// @ToDo find out why
							echo '<p>Check database does not seem to work at this time</p>';

							$status = explode( "\n", $status );
							$status = array_filter( $status );

							foreach ( $status as $info ) {
								echo '<div class="alert alert-success alert-dismissible" role="alert"><p>' . $info . '</p></div>';
							} // end foreach
							unset( $info );
							break;

						case 'db_optimize' :
							echo '<h4>Optimize Database</h4>';
							$status = $database_commands->database_optimize();
							$status = explode( "\n", $status );
							$status = array_filter( $status );

							foreach ( $status as $info ) {
								echo '<div class="alert alert-success alert-dismissible" role="alert"><p>' . $info . '</p></div>';
							} // end foreach
							unset( $info );

							break;

						case 'db_repair' :
							echo '<h4>Repair Database</h4>';
							$status = $database_commands->database_repair();
							$status = explode( "\n", $status );
							$status = array_filter( $status );

							foreach ( $status as $info ) {
								echo '<div class="alert alert-success alert-dismissible" role="alert"><p>' . $info . '</p></div>';
							} // end foreach
							unset( $info );
							break;

						case 'db_tables' :
							echo '<h4>Database Tables</h4>';
							$status = $database_commands->get_tables();
							$status = explode( "\n", $status );
							foreach ( $status as $info ) {
								echo '<p>' . $info . '</p>';
							} // end foreach
							unset( $info );
							break;
					}
				}

				?>
			</div>
		</div>
	</div>
	<?php
	$backups_table = $database_commands->get_host_backups_table();

	if ( ! empty( $backups_table ) ) {
		?><h4 class="title">Current Host Backups</h4>

		<?php
		echo $backups_table;
	}
	?>

	<h4 class="title"><?php echo $close; ?></h4>
<?php } else { ?>
	<h4 class="title">Manage Site Databases <span class="small"></span> <?php echo $close; ?></h4>
	<div class="alert alert-danger alert-dismissible" role="alert">
		<strong>ERROR</strong><br> You have not selected a host.
	</div>
<?php }

// End database.php