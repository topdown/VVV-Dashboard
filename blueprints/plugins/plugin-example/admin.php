<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/13/16, 1:06 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * {{slug}}
 *
 * admin.php
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


// Hook for adding admin menus
add_action('admin_menu', '{{prefix}}_add_pages');

// action function for above hook
function {{prefix}}_add_pages() {
    // Add a new submenu under Settings:
    add_options_page(__('Test Settings','menu-test'), __('Test Settings','menu-test'), 'manage_options', 'testsettings', '{{prefix}}_settings_page');

    // Add a new submenu under Tools:
    add_management_page( __('Test Tools','menu-test'), __('Test Tools','menu-test'), 'manage_options', 'testtools', '{{prefix}}_tools_page');

    // Add a new top-level menu (ill-advised):
    add_menu_page(__('Test Toplevel','menu-test'), __('Test Toplevel','menu-test'), 'manage_options', '{{prefix}}-top-level-handle', '{{prefix}}_toplevel_page' );

    // Add a submenu to the custom top-level menu:
    add_submenu_page('{{prefix}}-top-level-handle', __('Test Sublevel','menu-test'), __('Test Sublevel','menu-test'), 'manage_options', 'sub-page', '{{prefix}}_sublevel_page');

    // Add a second submenu to the custom top-level menu:
    add_submenu_page('{{prefix}}-top-level-handle', __('Test Sublevel 2','menu-test'), __('Test Sublevel 2','menu-test'), 'manage_options', 'sub-page2', '{{prefix}}_sublevel_page2');
}

// {{prefix}}_settings_page() displays the page content for the Test Settings submenu
function {{prefix}}_settings_page() {
    echo "<h2>" . __( 'Test Settings', 'menu-test' ) . "</h2>";
}

// {{prefix}}_tools_page() displays the page content for the Test Tools submenu
function {{prefix}}_tools_page() {
    echo "<h2>" . __( 'Test Tools', 'menu-test' ) . "</h2>";
}

// {{prefix}}_toplevel_page() displays the page content for the custom Test Toplevel menu
function {{prefix}}_toplevel_page() {
    echo "<h2>" . __( 'Test Toplevel', 'menu-test' ) . "</h2>";
}

// {{prefix}}_sublevel_page() displays the page content for the first submenu
// of the custom Test Toplevel menu
function {{prefix}}_sublevel_page() {
    echo "<h2>" . __( 'Test Sublevel', 'menu-test' ) . "</h2>";
}

// {{prefix}}_sublevel_page2() displays the page content for the second submenu
// of the custom Test Toplevel menu
function {{prefix}}_sublevel_page2() {
    echo "<h2>" . __( 'Test Sublevel2', 'menu-test' ) . "</h2>";
}

// End admin.php