<?php

namespace mmaurice\cabinet\helpers;

class SortingHelper
{
    static public function sortNestedArrays($array, $arguments = [])
    {
        return usort($array, function ($left, $right) use ($arguments) {
            $result = 0;

            foreach ($arguments as $key => $value) {
                if ($left[$key] === $right[$key]) {
                    continue;
                }

                if ($left[$key] < $right[$key]) {
                    $result = -1;
                }

                if ($left[$key] > $right[$key]) {
                    $result = 1;
                }

                if (strtoupper($value) === 'DESC') {
                    $result = (0 - $result);
                }

                break;
            }

            return $result;
        });
    }
}
