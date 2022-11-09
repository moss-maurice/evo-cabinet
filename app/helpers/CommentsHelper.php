<?php

namespace mmaurice\cabinet\helpers;

/**
 * Класс помощника для форматирования сообщений
 */
class CommentsHelper
{
    const PREVIEW_DEFAULT_LENGTH = 100;
    const SMART_LENGTH_OFF = false;
    const SMART_LENGTH_SPACE = ' ';
    const SMART_LENGTH_DOT = '.';
    const BODY_END = '...';
    const BODY_NO_END = false;

    static public function getPreview($content = '', $length = self::PREVIEW_DEFAULT_LENGTH, $smartLength = self::SMART_LENGTH_OFF, $bodyEnd = self::BODY_END)
    {
        $result = self::splitText($content, $length, $smartLength);

        if (!empty($result[1]) and ($bodyEnd !== self::BODY_NO_END)) {
            return $result[0] . '<span class="body_end">' . $bodyEnd . '</span>';
        }

        return $result[0];
    }

    static public function getMore($content = '', $length = self::PREVIEW_DEFAULT_LENGTH, $smartLength = self::SMART_LENGTH_OFF)
    {
        $result = self::splitText($content, $length, $smartLength);

        return $result[1];
    }

    protected static function splitText($content = '', $length = self::PREVIEW_DEFAULT_LENGTH, $smartLength = self::SMART_LENGTH_OFF)
    {
        $content = trim($content);

        if (!empty($content)) {
            if ($length > mb_strlen($content, 'UTF-8')) {
                $length = mb_strlen($content, 'UTF-8');
            }

            $contentPartLeft = mb_substr($content, 0, $length, 'UTF-8');
            $contentPartRight = mb_substr($content, $length, (mb_strlen($content, 'UTF-8') - $length), 'UTF-8');

            if (($smartLength !== self::SMART_LENGTH_OFF) and !empty($contentPartRight)) {
                $contentPartLeftLS = mb_substr(trim($contentPartLeft), (mb_strlen(trim($contentPartLeft), 'UTF-8') - 1), 1, 'UTF-8');

                if ($contentPartLeftLS !== $smartLength) {
                    $pos = mb_stripos($contentPartRight, $smartLength);

                    if ($pos > 0) {
                        $contentPartRightToLeft = mb_substr($contentPartRight, 0, ($pos + 1), 'UTF-8');
                        $contentPartLeft .= $contentPartRightToLeft;
                        $contentPartRight = mb_substr($contentPartRight, ($pos + 1), (mb_strlen($contentPartRight, 'UTF-8') - ($pos + 1)), 'UTF-8');
                    }
                }
            }

            $rtrim = self::SMART_LENGTH_DOT;

            if ($smartLength !== self::SMART_LENGTH_OFF) {
                $rtrim = $smartLength;
            }

            $contentPartLeft = rtrim(str_replace(chr(10), '<br /><br />', str_replace(array(chr(13), chr(10) . chr(10)), array(chr(10), chr(10)), $contentPartLeft)), $rtrim);
            $contentPartRight = str_replace(chr(10), '<br /><br />', str_replace(array(chr(13), chr(10) . chr(10)), array(chr(10), chr(10)), $contentPartRight));

            return array($contentPartLeft, $contentPartRight);
        }
        return array($content, '');
    }

    static public function filter($string)
    {
        return str_replace(array("\r\n", "\r", "\n", '\r\n', '\r', '\n', PHP_EOL), '<br />', $string);
    }
}
