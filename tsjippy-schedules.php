<?php

namespace TSJIPPY\EVENTS;

use TSJIPPY;

/**
 * Plugin Name:          Tsjippy Schedules
 * Description:          This plugin adds the possibility to create a schduled for one or more users.
 * Version:              1.0.7
 * Author:               Ewald Harmsen
 * AuthorURI:            harmseninnigeria.nl
 * Requires at least:    6.3
 * Requires PHP:         8.3
 * Tested up to:         6.9
 * Plugin URI:           https://github.com/Tsjippy/events/
 * Tested:               6.9
 * TextDomain:           tsjippy
 * Requires Plugins:     tsjippy-events
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @author Ewald Harmsen
 */
if (! defined('ABSPATH')) {
    exit;
}

// Load shared code
if(file_exists(__DIR__  . '/shared-functionality/loader.php')){
    require_once(__DIR__  . '/shared-functionality/loader.php');
}

// Define constants
define(__NAMESPACE__ . '\PLUGIN', plugin_basename(__FILE__));
define(__NAMESPACE__ . '\PLUGINPATH', __DIR__ . '/');
define(__NAMESPACE__ . '\PLUGINVERSION', get_plugin_data(__FILE__, false, false)['Version']);
define(__NAMESPACE__ . '\PLUGINSLUG', str_replace('tsjippy-', '', basename(__FILE__, '.php')));
define(__NAMESPACE__ . '\SETTINGS', get_option('tsjippy_schedules_settings', []));

// run right before activation
register_activation_hook(__FILE__, function () { 
    if(file_exists(__DIR__  . '/shared-functionality/loader.php')){
        require_once(__DIR__  . '/shared-functionality/loader.php');
    }

    $settings                    = SETTINGS;
    $settings['schedules-page']    = TSJIPPY\ADMIN\createDefaultPage('Schedules', '[tsjippy_schedules]');

    update_option('tsjippy_schedules_settings', $settings);
});

register_deactivation_hook(__FILE__, function () {
    foreach (SETTINGS['schedules-pages'] as $page) {
        // Remove the auto created page
        wp_delete_post($page, true);
    }
});
