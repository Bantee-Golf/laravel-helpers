<?php
namespace EMedia\Helpers;

use Carbon\Carbon;

class TimeHelper
{

    /**
     * Flexible human readable diff handler for Carbon
     * Returns future date as 'Closing in 5 days', 'Closing in 5 days from now', 'in 5 days'
     * Returns past dates as 'Closed 5 days ago', 'Closed 5 days before'
     *
     * @param Carbon|null $incomingDate
     *
     * @return string
     */
    public static function diffForHumans(
        Carbon $incomingDate = null,
        $absolute       = true,
        $emptyOnPast    = false,
        $futureTense    = '',
        $pastTense      = '',
        $futurePrefix   = 'in',
        $pastSuffix     = 'ago'
    ) {
        $isNow = $incomingDate === null;
        $now = Carbon::now($incomingDate->tz);

        if ($isNow) {
            $incomingDate = $now;
        }

        $inFuture = $incomingDate->gt($now);

        // returns as 'Closing in 2 weeks'
        if ($inFuture) {
            return $futureTense . " $futurePrefix " . $incomingDate->diffForHumans(null, $absolute);
        }

        if ($emptyOnPast) return '';

        return $pastTense . $incomingDate->diffForHumans(null, $absolute) . ' ' . $pastSuffix;

//
//        $delta = $incomingDate->diffInSeconds($now);
//
//        // a little weeks per month, 365 days per year... good enough!!
//        $divs = array(
//            'second' => Carbon::SECONDS_PER_MINUTE,
//            'minute' => Carbon::MINUTES_PER_HOUR,
//            'hour' => Carbon::HOURS_PER_DAY,
//            'day' => Carbon::DAYS_PER_WEEK,
//            'week' => 30 / Carbon::DAYS_PER_WEEK,
//            'month' => Carbon::MONTHS_PER_YEAR
//        );
//
//        $unit = 'year';
//
//        foreach ($divs as $divUnit => $divValue) {
//            if ($delta < $divValue) {
//                $unit = $divUnit;
//                break;
//            }
//
//            $delta = $delta / $divValue;
//        }
//
//        $delta = (int) $delta;
//
//        if ($delta == 0) {
//            $delta = 1;
//        }
//
//        $txt = $delta . ' ' . $unit;
//        $txt .= $delta == 1 ? '' : 's';
//
//        if ($isNow) {
//            if ($isFuture) {
//                return $txt . ' from now';
//            }
//
//            return $txt . ' ago';
//        }
//
//        if ($isFuture) {
//            return 'in ' . $txt;
//        }
//
//        return $txt . ' before';
    }

    /**
     * Convert an incoming string to a database compatible time format
     *
     * @param $string
     *
     * @return bool|string
     */
    public static function convertToDbTime($string)
    {
        return date('Y-m-d H:i:s', strtotime(trim($string)));
    }


	/**
     * Get the micro-timestamp of current time
     *
     * @return string
     */
    public static function getMicroTimestamp()
    {
        // get microtime of file generation to preserve migration sequence
        $time = explode(" ", microtime());

        // change the format to 2008_07_14_010813.98232
        return date("Y_m_d_His", $time[1]) . '.' . substr((string)$time[0], 2, 5);
    }

}