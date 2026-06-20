<?php

namespace TSJIPPY\EVENTS;

use TSJIPPY;

add_action('rest_api_init',  __NAMESPACE__ . '\blockRestApiInit');
function blockRestApiInit()
{
    // show schedules
    register_rest_route(
        TSJIPPY\RESTAPIPREFIX . '/events',
        '/show_schedules',
        array(
            'methods'                 => 'GET',
            'callback'                 => __NAMESPACE__ . '\showSchedules',
            'permission_callback'     => function ($rest) {
                return current_user_can('read');
            },
        )
    );
}

function showSchedules()
{
    $schedule        = new Schedules();
    return $schedule->showSchedules();
}

