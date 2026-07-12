<?php

namespace TSJIPPY\SCHEDULES;

use TSJIPPY;

if ( ! defined( 'ABSPATH' ) ) exit;

add_action('init', __NAMESPACE__ . '\initBlocks');
function initBlocks()
{
    register_block_type(
        'tsjippy-schedules/show-schedules',
        array(
            'title'            => __( 'Schedules', '%TEXTDOMAIN%' ),
            'render_callback'  => __NAMESPACE__ . '\displaySchedules',
            'supports'         => array(
                'autoRegister' => true,
            ),
            'icon'  => 'schedule'
        ),
    );
}

function displaySchedules()
{
    $schedule    = new Schedules();
    return $schedule->showSchedules();
}
