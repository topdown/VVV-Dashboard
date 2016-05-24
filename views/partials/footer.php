<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/20/15, 12:23 AM
 *
 * LICENSE:
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * footer.php
 */
?>
	<div class="container-fluid">

		<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">
			<p>
				<strong>NOTE: </strong>This Dashboard project has no affiliation with Varying Vagrant Vagrants or any other components listed here.
			</p>

			<p>
				<span class="pull-left">
					<small>VVV Dashboard Version: <?php echo VVV_DASH_VERSION; ?></small>
				</span>
			</p>
			<?php
			// Need to do some load testing
			global $timestart;
			$precision         = 3;
			$mtime             = microtime();
			$mtime             = explode( ' ', $mtime );
			$finish            = $mtime[1] + $mtime[0];
			$total_time        = round( ( $finish - $timestart ), 4 );
			$data['load_time'] = $total_time;

			if ( function_exists( 'memory_get_usage' ) ) {
				$data['memory'] = round( memory_get_usage() / 1048576, 2 );
			}

			if ( function_exists( 'memory_get_peak_usage' ) ) {
				$data['peak_mem'] = round( memory_get_peak_usage() / 1048576, 6 );
			}

			$data['includes_count'] = count( get_included_files() );

			if ( ini_get( 'apc.enabled' ) == true ) {
				$data['apc'] = 'APC Cache Enabled';
			}
			?>
			<div style="padding: 5px; font-size: 11px; float: right;">
				<style type="text/css">.mark-bl {padding-right: 10px;}</style>
				<?php

				echo '<span class="mark-bl">Load Time: ' . $data['load_time'] . '</span> ';
				echo '<span class="mark-bl">Memory Usage: ' . $data['memory'] . '</span> ';
				echo '<span class="mark-bl">Peak Memory Usage: ' . $data['peak_mem'] . '</span> ';
				echo '<span class="mark-bl">Included Files: ' . $data['includes_count'] . '</span> ';
				if ( isset( $data['apc'] ) ) {
					echo '<span class="mark-bl">' . $data['apc'] . '</span>';
				}

//				echo '<pre style="text-align: left;">' . "FILE: ". __FILE__ . "\nLINE: " . __LINE__ . "\n";
//				print_r(get_included_files());
//				echo '</pre>------------ Debug End ------------';
//				
				?>


			</div>
		</div>
	</div>
	</div>
	</body>
	</html>
<?php
// End footer.php