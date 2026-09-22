<?php

namespace TSJIPPY\SCHEDULES;

use TSJIPPY;

if (! defined('ABSPATH')) {
    exit;
}

add_action('wp_after_insert_post', __NAMESPACE__ . '\afterInsertPost', 10, 2);
/**
 * Set the default picture of a post after it is inserted
 *
 * @param    int        $postId        The WP_Post id
 * @param    \WP_Post    $post        The WP_Post object
 */
function afterInsertPost($postId, $post)
{
    if (has_shortcode($post->post_content, 'schedules')) {
        $pages          = SETTINGS['schedule-pages'] ?? [];

        $pages[$postId] = $postId;

        $settings       = SETTINGS;
        $settings['schedule-pages']  = $pages;

        update_option("tsjippy_events_settings", $settings);
    }
}

add_action('wp_trash_post',  __NAMESPACE__ . '\trashPost');
function trashPost($postId)
{
    $pages  = SETTINGS['schedule-pages'] ?? [];
    if ($pages[$postId]) {
        unset($pages[$postId]);
        $settings   = SETTINGS;
        $settings['schedule-pages']  = $pages;

        update_option("tsjippy_events_settings", $settings);
    }
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\loadAssets');
function loadAssets()
{
    if (str_contains($_SERVER['REQUEST_URI'], '.map')) {
        return;
    }

    /**
     * CSS
     */
    wp_register_style('tsjippy_schedules_css', TSJIPPY\pathToUrl(PLUGINPATH . 'css/schedules.min.css'), array(), PLUGINVERSION);

    /**
     * Modules
     */

    $deps   = SCRIPT_DEBUG ? [  
        '@tsjippy/form_submit_functions',
        "@tsjippy/show_loader", 
        "@tsjippy/display_message", 
        "@tsjippy/modals",
        "@tsjippy/alert"
    ] :
    [];
    wp_register_script_module('@tsjippy/schedules_shared', TSJIPPY\pathToUrl(PLUGINPATH . 'js/modules/shared.js'), $deps, PLUGINVERSION);

    /**
     * Scripts
     */
    if (wp_is_mobile()) {
        $deps   = SCRIPT_DEBUG ? [  
            '@tsjippy/schedules-shared', 
            "@tsjippy/form_submit_functions", 
            "@tsjippy/display_message", 
            "@tsjippy/modals"
        ] :
        [];
        wp_register_script_module('@tsjippy/schedules_script', TSJIPPY\pathToUrl(PLUGINPATH . 'js/mobile' . TSJIPPY\JSEXTENSION), $deps, PLUGINVERSION);
    } else {
        $deps   = SCRIPT_DEBUG ? [  
            '@tsjippy/schedules_shared', 
            "@tsjippy/form_submit_functions", 
            "@tsjippy/show_loader", 
            "@tsjippy/display_message", 
            "@tsjippy/modals",
            "@tsjippy/alert",
            "@tsjippy/mobile"
        ] :
        [];

        $deps[] = '@tsjippy/table_script';
        $deps[] = 'selectable';
        wp_register_script_module('@tsjippy/schedules_script', TSJIPPY\pathToUrl(PLUGINPATH . 'js/desktop' . TSJIPPY\JSEXTENSION), $deps, PLUGINVERSION);
    }

    add_filter( 'script_module_data_@tsjippy/schedules_script', function($data){
        $data['userId']       = get_current_user_id();

        return $data; 
    } );

    $schedulePages         = SETTINGS['schedule-pages'] ?? [];
    if (is_numeric(get_the_ID())) {
        if (in_array(get_the_ID(), $schedulePages)) {
            wp_enqueue_style('tsjippy_schedules_css');

            wp_enqueue_script_module('@tsjippy/schedules_script');
        } 
    }
}
