<?php

namespace TSJIPPY\SCHEDULES;

use TSJIPPY;

if (! defined('ABSPATH')) {
    exit;
}
add_filter('tsjippy-frontend-content-post-edit-button', __NAMESPACE__ . '\editButton', 10, 3);
function editButton($buttonHtml, $post, $content)
{
    if ($post->post_type != 'event') {
        return $buttonHtml;
    }

    global $wpdb;

    $schedules  = new Schedules();

    $result = TSJIPPY\getFromDb(
        "get_schedule_by_post_id",
        "schedules",
        "SELECT * FROM %i WHERE `post_ids` LIKE %s",
        $schedules->sessionTableName,
        "%" . $wpdb->esc_like($post->ID) . "%"
    );

    if (!empty($result)) {;
        $url        = TSJIPPY\ADMIN\getDefaultPageLink('events', 'schedules-pages') . "?schedule={$result[0]->schedule_id}&session={$result[0]->id}";

        $buttonHtml = "<a href=$url class='button small'>Edit this schedule session</a>";
    }

    return $buttonHtml;
}

/**
 * Add a description to the schedules page
 */
add_filter('display_post_states', __NAMESPACE__ . '\postStates', 10, 2);
function postStates($states, $post)
{

    if ($post->ID == (SETTINGS['schedules-page'] ?? createDefaultPages('schedules-page'))) {
        $states[] = __('Schedules page', '%TEXTDOMAIN%');
    }

    return $states;
}
