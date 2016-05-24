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
 */
$host = isset($_GET['host']) ? $_GET['host']: die('No Host given');
?>
	<form class="create-plugin" action="" method="post">
		<input type="hidden" name="host" value="<?php echo $host; ?>" />

		<p class="plugin-input">
			<label>Plugin Slug</label>
			<input class="plugin-slug" type="text" placeholder="plugin_slug" name="plugin_slug" value="" />
			<button class="add-post-type btn btn-default btn-xs">Add Post Type</button>
		</p>

		<p>
			<button type="submit" class="btn btn-success btn-xs" name="create_plugin" value="Create Plugin">
				<i class="fa fa-puzzle-piece"></i> Create Plugin
			</button>
			&nbsp; &nbsp; &nbsp;<label>Skip Tests</label> &nbsp; <input type="checkbox" name="skip_tests" />

		</p>
	</form><br />
<?php

// End create_plugin.php