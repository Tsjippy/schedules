<?php

namespace TSJIPPY\EVENTS;

use TSJIPPY;

add_action('init', function () {
    register_block_type(
        'tsjippy-schedules/show_schedules',
        array(
            'title'            => __( 'Schedules', 'tsjippy' ),
            'render_callback'  => __NAMESPACE__ . '\displaySchedules',
            'supports'         => array(
                'autoRegister' => true,
            ),
        )
    );
});

function displaySchedules()
{
    $schedule    = new Schedules();
    return $schedule->showSchedules();
}
