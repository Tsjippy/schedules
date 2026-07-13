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

    //css
    wp_register_style('tsjippy_schedules_css', TSJIPPY\pathToUrl(PLUGINPATH . 'css/schedules.min.css'), array(), PLUGINVERSION);

    //js
    if (wp_is_mobile()) {
        wp_register_script('tsjippy_schedules_script', TSJIPPY\pathToUrl(PLUGINPATH . 'js/mobile-schedule.min.js'), array('tsjippy_formsubmit_script'), PLUGINVERSION, true);
    } else {
        wp_register_script('tsjippy_schedules_script', TSJIPPY\pathToUrl(PLUGINPATH . 'js/desktop-schedule.min.js'), array('tsjippy_table_script', 'selectable', 'tsjippy_formsubmit_script'), PLUGINVERSION, true);
    }

    $schedulePages         = SETTINGS['schedule-pages'] ?? [];
    if (is_numeric(get_the_ID())) {
        if (in_array(get_the_ID(), $schedulePages)) {
            wp_enqueue_style('tsjippy_schedules_css');

            wp_enqueue_script('tsjippy_schedules_script');
        } 
    }
}
