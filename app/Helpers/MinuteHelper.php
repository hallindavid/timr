<?php

namespace App\Helpers;

class MinuteHelper
{
    public static function format_minutes($minutes)
    {
        if (empty($minutes)) {
            return '-';
        }

        $minutes = intval($minutes);
        if ($minutes < 60) {
            return $minutes . ' min';
        }

        return floor($minutes / 60) . ' hrs'
            . (($minutes % 60) > 0 ? ($minutes % 60) . ' min' : '');
    }
}
