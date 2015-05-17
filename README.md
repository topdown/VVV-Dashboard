This is a Varying Vagrant Vagrants Dashboard for the excellent [Varying Vagrant Vagrants](https://github.com/Varying-Vagrant-Vagrants/VVV)

Its purpose is to dynamically load host links to all sites created in the VVV www path.

It also suggests the wonderful add-on bash script [VVV Site Wizard](https://github.com/aliso/vvv-site-wizard) for creating new sites.

Instructions
-
Clone this repo to your VVV/www/default/ directory (`git clone https://github.com/topdown/VVV-Dashboard.git dashboard`)

Copy the dashboard-custom.php to VVV/www/default/dashboard-custom.php

---
### UPDATE Instructions 
From your dashboard directory ```git pull```

You no longer need to copy the style.css anywhere.

Delete the old VVV/www/default/dashboard-custom.php and copy the new version to VVV/www/default/dashboard-custom.php

Now move your dashboard directory so it is inside VVV/www/default/

---

With Vagrant Up you should be able browse to your vagrant root www usually [vvv](http://vvv) or [vvv.dev](http://vvv.dev) and see the new dashboard.

** NEW: There are bound keys for the search feature, the enter key and down arrow key search down the list and the up arrow searches up.**


![image](https://raw.githubusercontent.com/topdown/VVV-Dashboard/master/screenshot.png)

---
** NOTE: ** This Dashboard project has no affiliation with Varying Vagrant Vagrants or any other components listed here.

---

### Change Log

---
5/17/2015

* code cleanup

---
5/16/2015

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
Version: 0.0.4
