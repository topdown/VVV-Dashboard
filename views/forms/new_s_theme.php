<?php

/**
 *
 * PHP version 5
 *
 * Created: 5/6/16, 1:34 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * new_s_theme.php
 */
?>
	<form class="create-s-theme" action="" method="post">
		<span class="italic bold">Create a new theme based on <a href="http://underscores.me/" target="_blank">_s</a> :
		</span> <input type="hidden" name="host" value="<?php echo $_GET['host']; ?>" />
		<input class="child-name" placeholder="Theme Name" type="text" name="theme_name" value="" />
		<input class="child-slug" placeholder="theme_slug" type="text" name="theme_slug" value="" />
		<button type="submit" class="btn btn-success btn-xs" name="create_s_theme" value="Create _s Theme">
			<i class="fa fa-paint-brush"></i> Create _s Theme
		</button>
	</form>
<?php
// End new_s_theme.php