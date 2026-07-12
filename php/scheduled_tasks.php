<?php

namespace TSJIPPY\SCHEDULES;

use TSJIPPY;

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', __NAMESPACE__ . '\scheduleTasks');
/**
 * Schedule all tasks for this plugin
 */
function scheduleTasks()
{
    TSJIPPY\scheduleTask('tsjippy-schedules-remove-old-schedules', 'daily', __NAMESPACE__, 'removeOldSchedules');
}

/**
 * Get all schedules with an end_date in the past and deletes them
 */
function removeOldSchedules()
{
    $schedules    = new CreateSchedule();
    $schedules->getSchedules();

    foreach ($schedules->schedules as $schedule) {
        if ($schedule->end_date < gmdate('Y-m-d')) {
            $schedules->removeSchedule($schedule->id);
        }
    }
}
