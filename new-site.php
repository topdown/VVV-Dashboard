<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/21/15, 12:44 PM
 *
 * LICENSE: This is PRIVATE source code developed for clients.
 * It is in no way transferable and copy rights belong to Jeff Behnke @ ValidWebs.com
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * new-site.php
 */


define( 'VVV_DASH_BASE', true );
define( 'VVV_WEB_ROOT', '/srv/www' );
define( 'VVV_DASH_VERSION', '0.0.9' );

// Settings
$path = '../../';
include_once '../dashboard-custom.php';

include_once 'views/header.php';
include_once 'views/navbar.php';

if(isset($_POST['new-site'])) {

}
?>
	<div class="container-fluid">

		<?php include_once 'views/sidebar.php' ?>

		<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">

			<h2>New VVV Site Setup</h2>
			<form id="create-site-form" action="" method="post">

				<p>
					<label for="site-name">Name of new site directory <em>(small letters and underscores)</em></label>
					<input type="text" name="site-name" id="site-name">
				</p>
				<!--
				Domain to use (leave blank for test.dev):
				Git repo to clone as wp-content (leave blank to skip):
				Local SQL file to import for database (leave blank to skip):

				About to perform the following:
* Halt Vagrant (if running)
* Create directory test in /Users/jeff/wp-vagrant/www
* Create files vvv-init.sh, wp-cli.yml, and vvv-hosts in directory test
* Create file test.conf in /Users/jeff/wp-vagrant/config/nginx-config/sites
* Run `vagrant up --provision` to initialize site

Provisioning Vagrant will do the following:
* Create database test
* Install WordPress (release version) in the htdocs directory
* Make the site visible at test.dev -->


				<p>
					<label for="is-wordpress">Is WordPress</label>
					<input type="checkbox" name="is-wordpress" id="is-wordpress" />
				</p>

				<p>
					<label for="debug">Debug On</label>
					<input type="checkbox" name="debug" id="debug" />
				</p>

				<input class="btn btn-primary btn-xs" type="submit" name="new-site" value="Create New Site" />
			</form>
		</div>
	</div>
<?php
include_once 'views/footer.php';

// End new-site.php