<?php

namespace mmaurice\cabinet\helpers;

class ConsoleHelper
{
    static public function arrToStr($array)
    {
        if (is_array($array) and !empty($array)) {
            if (count($array) === 1) {
                return "[{$array[0]}]";
            } else if (count($array) === 2) {
                return "[" . implode(", ", $array) . "]";
            } else if (count($array) > 2) {
                return "[" . array_shift($array) . " ... " . array_pop($array) . "]";
            }
        }

        return $array;
    }
}
