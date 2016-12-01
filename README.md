This is a Varying Vagrant Vagrants Dashboard for the excellent [Varying Vagrant Vagrants](https://github.com/Varying-Vagrant-Vagrants/VVV)

Its purpose is to dynamically load host links to all sites created in the VVV www path along with a long list of additional tools. See Feature list below.

[![Gitter Chat](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/topdown/VVV-Dashboard?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Setup
-
Clone this repo to your VVV/www/default/ directory and then copy dashboard-custom.php there:

```sh
cd www/default
git clone https://github.com/topdown/VVV-Dashboard.git dashboard
cp dashboard/dashboard-custom.php .
```

While VVV is running (`vagrant up`), the new dashboard is now viewable at your VVV root (usually [vvv](http://vvv) or [vvv.dev](http://vvv.dev)).

Update
-
Update your repo via `git pull` and then copy dashboard-custom.php to your default directory.
```sh
cd www/default/dashboard
git pull
cp dashboard-custom.php ..
```

---
### Help Grow This Feature List
<span class="badge-paypal"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KEUN2SQ2VRW7A" title="Donate to this project using Paypal"><img src="https://img.shields.io/badge/paypal-donate-yellow.svg" alt="PayPal donate button" /></a></span>

---

### Feature List

* List hosts in VVV
  * Debug On/Off for WP sites
    * Show not installed if wp-config.php is missing
  * Multisite detection and lists sub-sites under the parent host
  * WP Version for each host
  * Visit Site Link
  * Visit Admin Link
  * Profiler Link
  * Backup Database (SQL Dump) host-timestamped name
  * Debug Log viewer if debug log is found
    * Delete log
  * Drag and drop sorting, stored in a 30 day cookie
* Live/Fuzzy search host list
* List Plugins for each installed WordPress Site
  * Plugin Name
  * Status (inactive / active) highlighted
  * Update (None / Available - Update Button)
  * Version
* List Themes
  * Theme Name
    * Create Child Theme
  * Status (inactive / active / parent) highlighted
  * Update (None / Available - Update Button)
  * Version
* Backup List
  * Host
  * Date of backup
  * Time of backup
  * Live search
  * Actions
    * Save As (In case you want to save another copy somewhere else)
    * Roll Back (Roll back to any existing version for the host)
    * Delete (Allows you to selectively delete backups)
* Last 10 PHP Errors (with highlighting)
* Show Hide Sidebar
* Quick server info
* Command list
* Reference links
* Cache
  * Theme list for each site
  * Plugin list for each site
  * Host list ( Speeds up loading greatly )
  * VVV Dashboard version check
  * Delete cache for Hosts, Themes or Plugins
  * 24 hour cache for each system, separate TTL settings
* VVV Dashboard version check with notice
* All buttons are colored for separation
* Twitter Bootstrap theme
* Sass CSS
* Bower to manage JS
* xDebug check and notice to show if its on or off
* Debug Log viewer, if there is a debug.log show button by host and make the last 20 viewable
* WPStarter support
* .env check for .env type installs
* Custom setting for scan paths
* Custom setting for wp-content paths
* Migrations for databases. You can now migrate to a new domain.

---

### Note: Recent Changes 
You no longer need to copy the style.css anywhere.

There is now a cache system. If you don't see cache files on the first dashboard load, make sure dashboard/cache/ is writable (it should be).

There are bound keys for the search feature: the enter key and down arrow key search down the list, and the up arrow searches up.

---

### Customizations

There are some customizations that you can do to VVV Dashboard

1. change settings in the `VVV/www/default/dashboard-custom.php`
2. create a custom.css and add custom CSS. This file is ignored when updating and autoloaded if exists.

Want the host list full view all the time with no scolling/overflow

create a `VVV/www/default/dashboard/custom.css` file and add this

```
.sites {
	max-height: 100%;
    overflow-y: auto;
    overflow-x: auto;
}
```

![image](https://raw.githubusercontent.com/topdown/VVV-Dashboard/master/screenshots/screenshot-v0.1.3.png)

![image](https://raw.githubusercontent.com/topdown/VVV-Dashboard/master/screenshots/live-search.gif)

** More Screenshots**

[New Buttons](https://raw.githubusercontent.com/topdown/VVV-Dashboard/master/screenshots/host-list.png)
[Quick Server Info](https://raw.githubusercontent.com/topdown/VVV-Dashboard/master/screenshots/server-info.png)
[Theme List](https://raw.githubusercontent.com/topdown/VVV-Dashboard/master/screenshots/theme-list.png)
[Plugin List](https://raw.githubusercontent.com/topdown/VVV-Dashboard/master/screenshots/plugin-list.png)


---
** NOTE: ** This Dashboard project has no affiliation with Varying Vagrant Vagrants or any other components listed here.

---

### Change Log

---

mm/dd/yy

11/08/16 version: 0.1.9

* Fixes #49 PHP Warning: Invalid argument supplied for foreach() hosts-list2.php on line 80
* Added a host tools page with database tools for each site
* A good start on plugin blueprints


11/08/16 version: 0.1.8

* Make it possible to delete WP error logs
* Improve sidebar slide #44 props @josephfusco 
* Update version check #46 props @gwelser 
* Handle error opening stream #41 props @gwelser 
* Fix first startup cache errors do to missing cache dir #39


05/24/16  version: 0.1.7

* Fixed host debug log empty #30
* Multisite detection and lists sub-sites under the parent host #32


05/24/16  version: 0.1.6

* Drag and drop sorting of the host list/table stored in a 30 day cookie #28


05/09/16  version: 0.1.5

* Lots of changes, refactoring both actions and hosts #17 #26 and #25
* New hosts objects allows separating different types of hosts and hopefully fixes all past issues related to hosts #25
* New commands/action objects handles all of the theme, plugins, backups, etc... #17  #26
* Added branch specific version check so if you are running a branch other than mater you will get a notice for updates in that branch.
* Folders in the {VVV}/www/ directory that start with a _ underscore are tracked and marked as archives in the dashboard so you can archive sites in a directory like _archives
* Live search in backups


12/12/15  version: 0.1.4

* Added WPStarter support by fixing some path issues
* Added .env check and handling functions
* Started some refactoring adding new classes that will support the system
* Added Backup List and Backup List button
  * Host
  * Date of backup
  * Time of backup
  * Actions
    * Save As (In case you want to save another copy somewhere else)
    * Roll Back (Roll back to any existing version for the host)
    * Delete (Allows you to selectively delete backups)
* Added WordPress version to Host List
* Show not installed if wp-config.php is missing
* Custom setting for scan paths
* Custom setting for wp-content paths
* Create a child theme of any parent from themes list
* Deeper .env support but must follow the installers path
* Create a new theme under a host based on _s http://underscores.me/  and activate it. It also includes the sass files.
* Migrations for databases. You can now migrate to a new domain.

---
12/01/15  version: 0.1.3

* Bumping version again to resolve cache issues


---
12/01/15  version: 0.1.2

* Added APP icons (favicon) for the dashboard
* Added WP Debug Log viewer last 20 logs

---
11/22/15  version: 0.1.1

* Bug fixes for path issues


---
11/22/15  version: 0.1.0

* Added DB Backups to the dashboard


---
11/20/15  version: 0.0.9

* Added version check for VVV Dashboard
* Version check notice if a new version is available
* Display the last 10 PHP errors

---
11/20/15  version: 0.0.8

* Added update capabilities for plugins and themes
* Add mailcatcher menu item props: @atimmer topdown/VVV-Dashboard/pull/9

---
11/20/15  version: 0.0.7

* Added a scan depth setting
* Added purge forms for each of the cached systems
* Started cleaning up the index and moving HTML to views
* Added the ability for people to create custom.css to override our styles
* Added version constant to static files


---
11/19/2015 version: 0.0.6

* Added a simple Cache System to improve performance when collecting data
* Added get_plugins feature which will collect the plugins info from a selected installed site
* Added get_themes feature which will collect the themes info from a selected installed site
* Added quick server info to the sidebar
* Show/Hide sidebar
* Cookies for sidebar state (7 day cookie)
* Added some defined constants to the dashboard-custom.php (which means updating you need to replace the old)
* Added more screenshots
* Moved screenshots
* Updated Readme

---
5/18/2015  version: 0.0.5

* Added VV Command table
* Changed host list to responsive tables
* Changed host search to a live search

---
5/17/2015

* created develop branch

---
5/16/2015  version: 0.0.4

* Refactored getHosts function to also check if WP_DEBUG is true in each host
* Added it to the host list.
* Added host count
* Updated Screenshot
* Made the main view port wider moving commands to the sidebar
* Support for none WordPress sites

---
5/15/2015

* Update bower components
* Added jQuery
* Added Search Hosts feature
* Added max-height with automated scroll
* Updated install instruction and ReadMe
* Updated Screenshot

---

### These projects are used in VVV Dashboard

* [Twitter Bootstrap](https://github.com/twbs/bootstrap)
* [WPCLI](https://github.com/wp-cli/wp-cli)
* [jQuery](https://github.com/jquery/jquery)
* [Bower](https://github.com/bower/bower)
* [Fontawesome](https://github.com/FortAwesome/Font-Awesome)
* [Compass](https://github.com/Compass/compass)

---

### ToDo's

---

* Maybe some wiki docs since this is getting a little bigger
* Fuzzy search for plugins and themes ? not sure yet
* Refactor time, clean up some code and remove redundancy
* Dropdown Menu for tools but not Bootstrap it should be CSS only
* We need some form security added
* More settings for theme creation


