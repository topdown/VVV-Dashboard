<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/20/15, 12:29 AM
 *
 * LICENSE:
 *
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2015 ValidWebs.com
 *
 * dashboard
 * commands-table.php
 */
?>
	<h1>To easily spin up new WordPress sites</h1>

	<p>
		Install and use <a target="_blank" href="https://github.com/bradp/vv">Variable VVV (newest)</a><br />
		<a target="_blank" href="https://github.com/bradp/vv#vv-options">VV Options</a><br />
		<a target="_blank" href="https://github.com/bradp/vv#options-for-site-creation">VV Site Create Options</a>

	</p>

	<h2>Variable VVV Commands</h2>

	<table class="table table-responsive table-bordered table-striped">
		<thead>
		<tr>
			<th>Command</th>
			<th>Description</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>
				list or --list or -l
			</td>
			<td>
				List all VVV sites
			</td>
		</tr>
		<tr>
			<td>
				create or --create or -c
			</td>
			<td>
				Create a new site
			</td>
		</tr>
		<tr>
			<td>
				remove or --remove or -r
			</td>
			<td>
				Remove a site
			</td>
		</tr>
		<tr>
			<td>
				deployment-create or --deployment-create
			</td>
			<td>
				Set up deployment for a site
			</td>
		</tr>
		<tr>
			<td>
				deployment-remove or --deployment-remove
			</td>
			<td>
				Remove deployment for a site
			</td>
		</tr>
		<tr>
			<td>
				deployment-config or --deployment-config
			</td>
			<td>
				Manually edit deployment configuration
			</td>
		</tr>
		<tr>
			<td>
				blueprint-init or --blueprint-init
			</td>
			<td>
				Initialize blueprint file
			</td>
		</tr>
		<tr>
			<td>
				vagrant v --vagrant -v
			</td>
			<td>
				Pass vagrant command through to VVV
			</td>
		</tr>

		</tbody>
	</table>
<?php
// End commands-table.php