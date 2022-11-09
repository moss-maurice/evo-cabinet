<?php

namespace mmaurice\cabinet\helpers;

use mmaurice\cabinet\core\providers\ModxProvider;
use \Zelenin\SmsRu\Auth\ApiIdAuth;
use \Zelenin\SmsRu\Entity\Sms;
use \Zelenin\SmsRu\Api;

class SMSHelper
{
    /**
     * Установка клиента, перед отправкой сообщения. 
     *
     * @param void
     * @return \Zelenin\SmsRu\Api
     */
    static public function client()
    {
        return new Api(
            new ApiIdAuth(ModxProvider::getConfig('client_SmsRuKey'))
        );
    }

    /**
     * Отправка SMS сообщения.
     *
     * @param string $phone
     * @param string $message
     * @return void
     */
    static public function sendMessage($phone, $message)
    {
        if (!empty($message) and self::validatePhone($phone)) {
            self::client()->smsSend(
                new Sms($phone, $message)
            );
        }
    }

    /**
     * Отправка SMS сообщения с кодом подтверждения,
     * сохранение кода => номера телефона.
     *
     * @param string $phone
     * @return void
     */
    static public function sendCode($phone)
    {
        if ($code = SMSHelper::generateCode()) {
            SMSHelper::saveCode($phone, $code);

            self::client()->smsSend(
                new Sms($phone, $code)
            );
        }
    }

    /**
     * Сервисный метод сохранения SMS-кода и номера.
     *
     * @param string $phone
     * @param string $code
     * @return void
     */
    static public function saveCode($phone, $code)
    {
        global $modx;

        $modx->db->insert([
            'login' => $phone,
            'code' => $code
        ], $modx->getFullTableName('phones_confirm_codes'));

        return $modx->db->getInsertId();
    }

    /**
     * Сервисный метод создания SMS-кода.
     *
     * @return string
     */
    static public function generateCode()
    {
        $code = mb_substr(str_replace('.', '', mb_stristr(microtime(true), '.')) . rand(0, 9999), 0, 4);

        if (iconv_strlen($code) < 4) {
            $code = mb_substr($code .= rand(0, 9999), 0, 4);
        }

        return $code;
    }

    /**
     * Сервисный метод проверки SMS-кода => пароля.
     *
     * @param string $phone
     * @param string $code
     * @return void
     */
    static public function checkCode($phone, $code)
    {
        global $modx;

        $resource = $modx->db->select('id', $modx->getFullTableName('phones_confirm_codes'), "login='" . $phone . "' AND code='" . $code . "'");

        if ($modx->db->getRecordCount($resource)) {
            return true;
        }

        return false;
    }

    /**
     * Сервисный метод вылидации номера телефона.
     *
     * @param string $phone
     * @return void
     */
    static public function validatePhone($phone)
    {
        return preg_match('/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/', $phone);
    }
}
