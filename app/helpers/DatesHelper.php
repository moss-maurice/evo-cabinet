<?php

namespace mmaurice\cabinet\helpers;

use \DateTime;
use mmaurice\cabinet\core\helpers\FormatHelper;

/**
 * Класс помощника для форматирования дат
 */
class DatesHelper
{
    static public $translations = [
        'months' => [
            'normal' => [
                'ru' => ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
                'en' => ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'],
            ],
            'short' => [
                'ru' => ['янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'],
                'en' => ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sen', 'okt', 'nov', 'dec'],
            ],
            'nums' => ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
        ],
        'weeks' => [
            'normal' => [
                'ru' => ['понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота', 'воскресенье'],
                'en' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
            ],
            'short' => [
                'ru' => ['пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс'],
                'en' => ['mo', 'tu', 'we', 'th', 'fr', 'sa', 'su'],
            ],
            'nums' => [0, 1, 2, 3, 4, 5, 6],
        ],
    ];

    public function translateMonthToText($month, $type = 'normal', $lang = 'ru')
    {
        if (array_key_exists('months', self::$translations) and array_key_exists($type, self::$translations['months']) and array_key_exists($lang, self::$translations['months'][$type]) and array_key_exists(intval($month) - 1, self::$translations['months'][$type][$lang])) {
            return self::$translations['months'][$type][$lang][intval($month) - 1];
        }

        return '';
    }

    static public function toPrintedDate($date)
    {
        $date = str_replace('T', '', $date);

        return str_replace(self::$translations['months']['normal']['en'], self::$translations['months']['normal']['ru'], strtolower(FormatHelper::dateConvert($date, 'Y-m-d H:i:s', $formatTo = 'd F Y')));
    }

    static public function msgDateFormat($date)
    {
        $result = '';

        preg_match('/^(\d+)\-(\d+)\-(\d+)\s(\d+)\:(\d+)\:(\d+)$/i', $date, $matches);

        if (!empty($matches)) {
            array_shift($matches);

            $year = intval($matches[0]);
            $month = intval($matches[1]);
            $day = intval($matches[2]);
            $hour = intval($matches[3]);
            $min = intval($matches[4]);
            $sec = intval($matches[5]);

            $result = $day . ' ' . self::$translations['months']['short']['ru'][$month - 1] . ' ' . $hour . ':' . $min;
        }
        return $result;
    }

    static public function msgListDateFormat($date)
    {
        $result = [];

        preg_match('/^(\d+)\-(\d+)\-(\d+)\s(\d+)\:(\d+)\:(\d+)$/i', $date, $matches);

        if (!empty($matches)) {
            array_shift($matches);

            $year = intval($matches[0]);
            $month = intval($matches[1]);
            $day = intval($matches[2]);
            $hour = intval($matches[3]);
            $min = intval($matches[4]);
            $sec = intval($matches[5]);

            if ($month < 10) {
                $month = '0' . $month;
            }

            if ($day < 10) {
                $day = '0' . $day;
            }

            if ($hour < 10) {
                $hour = '0' . $hour;
            }

            if ($min < 10) {
                $min = '0' . $min;
            }

            if ($sec < 10) {
                $sec = '0' . $sec;
            }

            $result[0] = $day . '.' . $month . '.' . $year;
            $result[1] = $hour . ':' . $min;
        }

        return $result;
    }

    static public function getShortSpelledDate($date)
    {
        return str_replace(self::$translations['months']['normal']['ru'], self::$translations['months']['short']['ru'], self::getSpelledDate($date));
    }

    static public function getSpelledDate($date)
    {
        return intval(FormatHelper::dateConvert($date, 'Y-m-d H:i:s', 'd')) . ' ' . str_replace(self::$translations['months']['nums'], self::$translations['months']['normal']['ru'], FormatHelper::dateConvert($date, 'Y-m-d H:i:s', 'm'));
    }

    static public function getTime($date)
    {
        return FormatHelper::dateConvert($date, 'Y-m-d H:i:s', 'H:i');
    }

    static public function getWeekDayShortNameDate($date)
    {
        $dayNum = intval(FormatHelper::dateConvert($date, 'Y-m-d H:i:s', 'w')) - 1;

        while ($dayNum < 0) {
            $dayNum = count(self::$translations['weeks']['nums']) + $dayNum;
        }

        return str_replace(self::$translations['weeks']['nums'], self::$translations['weeks']['short']['ru'], $dayNum);
    }

    static public function getDayDiff($dateBefore, $dateAfter)
    {
        $timestampBefore = FormatHelper::dateToTimestampConvert($dateBefore . '00:00:00', 'Y-m-d H:i:s');
        $timestampAfter = FormatHelper::dateToTimestampConvert($dateAfter . '00:00:00', 'Y-m-d H:i:s');

        $timestampDiff = $timestampAfter - $timestampBefore;

        return intval(ceil($timestampDiff / (60 * 60 * 24)));
    }
}
