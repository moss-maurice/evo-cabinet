<?php

namespace mmaurice\cabinet\helpers;

class MemoryHelper
{
    static public function getMemory($round = 2)
    {
        $memory = memory_get_usage();

        for ($i = 0; $i < 1000000; $i++) {
            $array[] = rand(0, 1000000);
        }

        $bytes = memory_get_usage() - $memory;

        return self::convertBytes($bytes, $round);
    }

    static public function convertBytes($bytes, $round = 2, $aliases = ['b', 'kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb'])
    {
        $i = 0;

        while (floor($bytes / 1024) > 0) {
            $i++;

            $bytes /= 1024;
        }

        return round($bytes, $round) . ' ' . $aliases[$i];
    }
}
