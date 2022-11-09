<?php

namespace mmaurice\cabinet\helpers;

class TimerHelper
{
    private static $start = .0;

    static public function start()
    {
        self::$start = microtime(true);
    }

    static public function finish($round = 2)
    {
        return round(microtime(true) - self::$start, $round) . ' sec.';
    }
}
