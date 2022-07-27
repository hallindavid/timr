<?php

namespace App\Helpers;

class MinuteHelper
{
    public static function format_minutes($minutes)
    {

        $sections = [];

        if (intval($minutes) > (60 * 24)) { // Days
            $days = floor($minutes / (60 * 24));
            $sections[] = $days . ' days';
            $minutes = $minutes % (60 * 24);
        }

        if (intval($minutes) >= 60) { // Hours
            $hours = floor($minutes / 60);
            $sections[] = $hours . ' hours';
            $minutes = $minutes % 60;
        }

        if (intval($minutes) > 0) {
            $sections[] = round($minutes) . ' min';
        }

        $output = trim(implode(" ", $sections));

        if (strlen($output) > 0) {
            return $output;
        }

        return '-';
    }
}
