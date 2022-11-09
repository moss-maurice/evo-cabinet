<?php

namespace mmaurice\cabinet\helpers;

/**
 * Класс помощника для форматирования цен
 */
class StringsHelper
{
    static public function ucfirstUtf8($str)
    {
        return mb_substr(mb_strtoupper($str, 'utf-8'), 0, 1, 'utf-8') . mb_substr($str, 1, mb_strlen($str) - 1, 'utf-8');
    }
}
