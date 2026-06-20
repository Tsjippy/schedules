<?php

namespace TSJIPPY\EVENTS;

use TSJIPPY;

if (! defined('ABSPATH')) {
    exit;
}

add_shortcode("tsjippy_schedules", __NAMESPACE__ . '\schedules');
function schedules()
{
    return displaySchedules();
}
