<?php

/**
 *
 * PHP version 5
 *
 * Created: 12/16/15, 5:58 PM
 *
 * LICENSE: MIT
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * about.php
 */
?>
	<h2>About VVV Dashboard</h2>

	<p>
		<a href="https://github.com/topdown/VVV-Dashboard" title="Star the project on Github"><img src="https://img.shields.io/github/stars/topdown/VVV-Dashboard.svg" /></a>

		<a href="https://github.com/topdown/VVV-Dashboard" title="Fork the project on Github"><img src="https://img.shields.io/github/forks/topdown/VVV-Dashboard.svg" /></a>

		<a href="https://gitter.im/topdown/VVV-Dashboard" title="Gitter Chat"><img src="https://badges.gitter.im/Join%20Chat.svg" /></a>
	</p>

<p>
	VVV Dashboard goal was to be a simple interface but has now grown into a full development tool for WordPress development.
</p>
<p>
	With a fill list a features to speed up the development process VVV Dashboard has become very powerful and growing with each push/merge.
</p>
<p>
	If there is anything you think should be added or change, create a ticket or a pull request.
</p>
<?php

$wpcli_version = shell_exec( 'wp  --info' );
echo '<p>' . str_replace( "\n", '<br />', $wpcli_version ) . '</p>';

// End about.php