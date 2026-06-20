<?php

namespace TSJIPPY\EVENTS;

use TSJIPPY;

add_action('init', function () {
    register_block_type(
        __DIR__ . '/schedules/build',
        array(
            'render_callback' => __NAMESPACE__ . '\displaySchedules',
        )
    );
});

function displaySchedules()
{
    $schedule    = new Schedules();
    return $schedule->showSchedules();
}
