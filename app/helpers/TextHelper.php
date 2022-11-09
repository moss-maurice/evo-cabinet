<?php

namespace mmaurice\cabinet\helpers;

class TextHelper
{
    static protected $translateAlias = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'ж' => 'j',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'shch',
        'ы' => 'y',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        'ъ' => '',
        'ь' => '',
    ];

    static public function translate($string)
    {
        $string = strval($string); // преобразуем в строковое значение
        $string = strip_tags($string); // убираем HTML-теги
        $string = str_replace([chr(10), chr(13), PHP_EOL], ' ', $string); // убираем перевод каретки
        $string = preg_replace("/\s+/", ' ', $string); // удаляем повторяющие пробелы
        $string = trim($string); // убираем пробелы в начале и конце строки
        $string = function_exists('mb_strtolower') ? mb_strtolower($string) : strtolower($string); // переводим строку в нижний регистр (иногда надо задать локаль)
        $string = strtr($string, self::$translateAlias);
        $string = preg_replace("/[^0-9a-z-_ ]/i", '', $string); // очищаем строку от недопустимых символов
        $string = str_replace([' ', '--'], '-', $string); // заменяем пробелы знаком минус

        return $string; // возвращаем результат
    }
}
