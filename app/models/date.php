<?php

namespace app\models;

class Date
{

    public static function Now()
    {
        date_default_timezone_set('Asia/Manila');
        $currentDate = date("Y-m-d");
        // Separate the date into day, month, and year
        list($year, $month, $day) = explode("-", $currentDate);

        return [
            'year' => $year,
            'month' => $month,
            'day' => $day
        ];
    }

    public static function dateTimeToday()
    {
        date_default_timezone_set('Asia/Manila');
        return $currentDate = date("Y-m-d h:i A");
    }
}