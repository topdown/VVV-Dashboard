<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 1:26 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * create_plugin.php
 *
 * @see \vvv_dash\commands\plugin::_create();
 *
 */
$host = isset( $_GET['host'] ) ? $_GET['host'] : die( 'No Host given' );

echo '<h3>Create a Plugin</h3>';

include_once VVV_DASH_ROOT . '/vvv_dash/blueprints.php';

$object = new \blueprints( $_GET['host'] );
//$all_blueprints = $object->get_all_blueprints();
$blueprints = $object->get_blueprints();
//$the_blueprint  = $object->get_blueprint( 'plugin-example' );

?>
	<form class="create-plugin" action="" method="post">
		<div class="form-group">
			<input type="hidden" name="host" value="<?php echo $host; ?>" />
		</div>
		<div class="form-group">
			<p class="plugin-input">
				<label>Plugin Slug</label>
				<input class="plugin-slug" type="text" placeholder="plugin_slug" name="plugin_slug" value="" />
				<button class="add-post-type btn btn-default btn-xs">Add Post Type</button>
			</p>
		</div>
		<div class="form-group">
			<p>
				<label for="skip_tests">Skip Tests</label> &nbsp
				<input id="skip_tests" type="checkbox" name="skip_tests" />
			</p>
		</div>

		<?php /*  Not ready yet


		<div class="form-group">
			<h4>Add / Use Blueprint</h4>
			<label for="blueprint">Blueprints</label> <select name="blueprint" id="blueprint">
				<option value="">---- Select One ----</option>
				<?php
				foreach ( $blueprints as $key => $blueprint ) {
					echo '<option value="' . $key . '">' . $key . '</option>';
				} ?>
			</select>
		</div>

        */ ?>

		<div class="form-group">
			<p>
				<button type="submit" class="btn btn-success btn-xs" name="create_plugin" value="Create Plugin">
					<i class="fa fa-puzzle-piece"></i> Create Plugin
				</button>
			</p>
		</div>


		<?php ?>
	</form><br />
<?php

// End create_plugin.php