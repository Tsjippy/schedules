<?php

namespace TSJIPPY\SCHEDULES;

use TSJIPPY;

/**
 * Plugin Name:          Tsjippy Schedules
 * Description:          This plugin adds the possibility to create a schedule for one or more users.
 * Version:              1.1.4
 * Author:               Ewald Harmsen
 * AuthorURI:            harmseninnigeria.nl
 * Requires at least:    6.3
 * Requires PHP:         8.3
 * Tested up to:         7.0
 * Plugin URI:           https://github.com/Tsjippy/events/
 * Tested:               7.0
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

    createDefaultPages();

    if(function_exists('TSJIPPY\activate')){
        \TSJIPPY\activate();
    }
});

register_deactivation_hook(__FILE__, function () {
    // Remove the auto created page
    wp_delete_post(SETTINGS['schedules-pages'] ?? -1, true);
});

/**
 * Creates default pages if needed
 * 
 * @param string    $returnKey  The key to return a value for, default empty
 */
function createDefaultPages($returnKey=''){
    /**
     *  Default pages
     */
    $settings    = SETTINGS;

    // Create frontend posting page
    if(!isset($settings['schedules-page'])){
        $settings['schedules-page']    = TSJIPPY\ADMIN\createDefaultPage('Schedules', '<!-- wp:tsjippy-schedules/show /-->');
    }

    update_option('tsjippy_schedules_settings', $settings);

    if(!empty($returnKey) && isset($settings[$returnKey])){
        return $settings[$returnKey];
    }
}