<?php

namespace mmaurice\cabinet\helpers;

use mmaurice\cabinet\models\WebUsersModel;

/**
 * Класс помощника для форматирования цен
 */
class PricesHelper
{
    static public function ceil($price)
    {
        global $modx;

        $priceCeilingField = $modx->config['client_priceCeilingField'];

        switch ($priceCeilingField) {
            case 'integer1':
                return floatval(ceil($price));
                break;
            case 'integer10':
                return floatval(ceil($price / 10) * 10);
                break;
            case 'integer100':
                return floatval(ceil($price / 100) * 100);
                break;
            case 'float2':
            default:
                return floatval($price);
                break;
        }
    }

    static public function format($price, $suffix = '')
    {
        return number_format($price, 2, ',', ' ') . $suffix;
    }

    /**
     * Возвращает сумму прописью
     * @author runcore
     * @uses morph(...)
     */
    static public function num2str($num, $ucfirst = false)
    {
        $nul = 'ноль';
        $ten = [
            ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
            ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ];
        $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
        $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
        $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];
        $unit = [
            ['копейка', 'копейки', 'копеек', 1],
            ['рубль', 'рубля', 'рублей', 0],
            ['тысяча', 'тысячи', 'тысяч', 1],
            ['миллион', 'миллиона', 'миллионов', 0],
            ['миллиард', 'милиарда', 'миллиардов', 0],
        ];

        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));

        $out = [];

        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) {
                if (!intval($v)) {
                    continue;
                }

                $uk = sizeof($unit) - $uk - 1;
                $gender = $unit[$uk][3];

                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));

                $out[] = $hundred[$i1]; # 1xx-9xx

                if ($i2 > 1) {
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
                } else {
                    $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                }

                if ($uk > 1) {
                    $out[] = self::morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
                }
            }
        } else {
            $out[] = $nul;
        }

        $out[] = self::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]);
        $out[] = $kop . ' ' . self::morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]);

        $result = trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));

        if ($ucfirst) {
            return ucfirst($result);
        }

        return $result;
    }

    static public function simpleNum2str($num, $ucfirst = false)
    {
        $nul = 'ноль';
        $ten = [
            ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
            ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ];
        $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
        $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
        $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];
        $unit = [
            ['', '', '', 1],
            ['', '', '', 0],
            ['тысяча', 'тысячи', 'тысяч', 1],
            ['миллион', 'миллиона', 'миллионов', 0],
            ['миллиард', 'милиарда', 'миллиардов', 0],
        ];

        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));

        $out = [];

        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) {
                if (!intval($v)) {
                    continue;
                }

                $uk = sizeof($unit) - $uk - 1;
                $gender = $unit[$uk][3];

                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));

                $out[] = $hundred[$i1]; # 1xx-9xx

                if ($i2 > 1) {
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
                } else {
                    $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                }

                if ($uk > 1) {
                    $out[] = self::morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
                }
            }
        } else {
            $out[] = $nul;
        }

        $result = trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));

        if ($ucfirst) {
            return ucfirst($result);
        }

        return $result;
    }

    /**
     * Склоняем словоформу
     * @ author runcore
     */
    static public function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;

        if ($n > 10 and $n < 20) {
            return $f5;
        }

        $n = $n % 10;

        if ($n > 1 and $n < 5) {
            return $f2;
        }

        if ($n == 1) {
            return $f1;
        }

        return $f5;
    }

    static public function extractComissionFromPrice($priceWithComission, $comissionPersent = 0)
    {
        return floatval($priceWithComission - (($priceWithComission / (100 + $comissionPersent)) * 100));
    }

    static public function matchPriceRoomOnly($nights = 1, $places = 1, $mealPrice = 0, $roomTotalPrice = 0)
    {
        if (is_null($roomTotalPrice)) {
            $roomTotalPrice = 0;
        }

        $roomTotalPrice = floatval($roomTotalPrice);

        $onlyMealPrice = self::matchPriceMealOnly($nights, $places, $mealPrice);

        return floatval($roomTotalPrice + $onlyMealPrice);
    }

    static public function matchPriceMealOnly($nights = 1, $places = 1, $mealPrice = 0)
    {
        if (is_null($mealPrice)) {
            $mealPrice = 0;
        }

        $mealPrice = floatval($mealPrice);

        $nights = intval($nights);
        $places = intval($places);

        return floatval($mealPrice * $places * $nights);
    }

    static public function matchPrice($nights = 1, $places = 1, $mealPrice = 0, $roomTotalPrice = 0, $arriveTotalPrice = 0, $comission = 0)
    {
        if (is_null($arriveTotalPrice)) {
            $arriveTotalPrice = 0;
        }

        if (is_null($comission)) {
            $comission = 0;
        }

        $places = intval($places);
        $arriveTotalPrice = floatval($arriveTotalPrice);

        $onlyRoomPrice = self::matchPriceRoomOnly($nights, $places, $mealPrice, $roomTotalPrice);

        $price = $onlyRoomPrice + ($arriveTotalPrice * $places);

        return self::applyCommision($price, $comission);
    }

    static public function applyCommision($price, $comission)
    {
        return floatval($price + (($price / 100) * $comission));
    }

    static public function matchTotalAdditionalServicesPrice(array $additionalServices)
    {
        $price = 0;

        if (!empty($additionalServices)) {
            foreach ($additionalServices as $additionalService) {
                if ($additionalService->required) {
                    $price += $additionalService->price;
                }
            }
        }

        return floatval($price);
    }

    static public function matchTotalInsurancePrice(array $insurance)
    {
        $price = 0;

        if (!empty($insurance)) {
            foreach ($insurance as $insuranceItem) {
                if ($insuranceItem->required) {
                    $price += $insuranceItem->price;
                }
            }
        }

        return floatval($price);
    }

    static public function applyAgencyComission(&$price, &$comissionPrice, $adults = 2, $childrens = 0)
    {
        $user = new WebUsersModel;

        if ($user->isAgent()) {
            $comission = intval($user->getSetting('comission', 0));
            $comissionChld = intval($user->getSetting('comission_chld', 0));

            $places = intval($adults) + intval($childrens);
            $pricePart = $price / $places;

            $comissionPrice = ((($pricePart * intval($adults)) / 100) * $comission) + ((($pricePart * intval($childrens)) / 100) * $comissionChld);
            $price -= $comissionPrice;

            return true;
        }

        return false;
    }
}
