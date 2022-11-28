<?php

namespace mmaurice\cabinet\controllers\api;

use mmaurice\cabinet\components\ApiControllerComponent;
use mmaurice\cabinet\controllers\MailerController;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\helpers\MailerHelper;
use mmaurice\cabinet\helpers\SMSHelper;
use mmaurice\cabinet\models\UserRolesModel;
use mmaurice\cabinet\models\WebUsersModel;

/**
 * Класс контроллера авторизации через SMS.
 */
class AuthController extends ApiControllerComponent
{
    /**
     * @var string $message
     * @var int $code
     */
    public $message = '';
    public $code = 400;
    public $redirectUrl = '';
    public $userData = [];

    protected function checkIsAjax($code = self::CODE_ERROR, $status = self::STATUS_ERROR, $message = self::MESSAGE_DEFAULT_CHECKISAJAX)
    {
        return true;
    }

    protected function render($parametrs = [], $code = self::CODE_SUCCESS, $status = self::STATUS_SUCCESS, $message = self::MESSAGE_DEFAULT_RENDER)
    {
        $result = [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'redirectUrl' => $this->redirectUrl ? $this->redirectUrl : '',
            'userData' => $parametrs,
        ];

        if (defined('LK_DEBUG') and (LK_DEBUG === true)) {
            $result['db_query_log'] = $_SESSION['db_query_log'];
        }

        return $this->renderAjax($result);
    }

    /**
     * Метод по-умолчанию
     *
     * @param void
     * @return void
     */
    public function actionIndex()
    {}

    /**
     * Метод авторизации.
     *
     * @param void
     * @return void
     */
    public function actionLogin()
    {
        global $modx;

        $userModel = new WebUsersModel;

        if (App::request()->isPostRequest()) {
            $authField = !in_array($modx->config['client_authField'], ['email', 'phone']) ? 'email' : $modx->config['client_authField'];

            $phone = App::request()->extractPost('phone', '');
            $email = App::request()->extractPost('email', '');
            $password = App::request()->extractPost('password');
            $login = '';

            /*if (($authField === 'phone') and SMSHelper::validatePhone($phone)) {
                $login = intval($phone);

                if (!empty($password) and !empty($login)) {
                    if ($userModel->checkUserIsset($login)) {
                        if (!$userModel->login($login, $password)) {
                            $this->code = 400;
                            $this->message = 'Wrong password';
                        } else {
                            $userId = intval($userModel->getIdByLogin($login)['id']);

                            if ($userId and $userId === $userModel->getId()) {
                                $this->userData = WebUsersModel::getUserDataByLogin($login);
                            }

                            $this->code = 200;
                            $this->message = 'Success login';
                            $this->redirectUrl = App::init()->makeUrl('/lk/');
                        }
                    } else {
                        $this->code = 400;
                        $this->message = 'User is not exist';
                    }
                } else {
                    $this->code = 400;
                    $this->message = 'Login or password is empty';
                }
            } else*/ if ($authField === 'email') {
                $login = $userModel->getLoginByEmail($email);

                if (!empty($password) and !empty($login)) {
                    if ($userModel->login($login, $password)) {
                        $userId = intval($userModel->getIdByLogin($login)['id']);

                        if ($userId and $userId === $userModel->getId()) {
                            $this->userData = WebUsersModel::getUserDataByLogin($login);
                        }

                        $this->code = 200;
                        $this->message = 'Success login';
                        $this->redirectUrl = App::init()->makeUrl('/lk/');
                    } else {
                        $this->code = 400;
                        $this->message = 'Login or password is not valide';
                    }
                } else {
                    $this->code = 400;
                    $this->message = 'Login or password is empty';
                }
            }
        } else {
            $this->code = 400;
            $this->message = 'Allowed only POST-requests';
        }

        return $this->render(($this->userData ? $this->userData[0] : []), self::CODE_SUCCESS, ($this->code ? $this->code : 400), ($this->message ? $this->message : 'Error'));
    }

    /**
     * Метод регистрации пользователя.
     *
     * @param void
     * @return void
     */
    public function actionRegister()
    {
        global $modx;

        $userModel = new WebUsersModel;

        if (App::request()->isPostRequest()) {
            $authField = !in_array($modx->config['client_authField'], ['email', 'phone']) ? 'email' : $modx->config['client_authField'];

            $firstName = App::request()->extractPost('firstName');
            $password = App::request()->extractPost('password');
            $passwordRetype = App::request()->extractPost('passwordRetype');
            $email = App::request()->extractPost('email', '');
            $phone = App::request()->extractPost('phone', '');
            //$login = '';

            // Проверим на значение поля метода регистрации
            // А так же на null, так как есть случаи, когда настройка по-умолчанию не задана.
            // В таком случае, null мы приравниваем к email.
            // Но при этом есть ещё миграция 0217_register_method_settings_fix.migration.sql, которая тоже должна закрыть этот косяк, но с другой стороны
            if (in_array($authField, ['phone', 'email']) or is_null($authField)) {
                /*if ($authField === 'phone') {
                    if (SMSHelper::validatePhone($phone)) {
                        $login = intval($phone);

                        if (!$userModel->checkUserIsset($login)) {
                            if (empty($code)) {
                                $code = SMSHelper::generateCode();

                                if (SMSHelper::saveCode($login, $code)) {
                                    SMSHelper::sendMessage($phone, "Ваш пароль: {$code}. Вы можете изменить пароль после авторизации");

                                    $this->message = 'Code had been sent';
                                    $this->code = 200;
                                }
                            } else {
                                if (SMSHelper::checkCode($login, $code)) {
                                    if ($userModel->register($login, $code, $code, $email, [
                                        'first_name' => $name,
                                        'last_name' => '',
                                        'middle_name' => '',
                                        'userUUID' => '',
                                        'phone' => $phone,
                                        'mobilephone' => $phone,
                                    ], intval($role) ? $role : 5)) {
                                        $this->message = 'Success registeration';
                                        $this->redirectUrl = App::init()->makeUrl('/{lk}/login');
                                        $this->code = 200;
                                    } else {
                                        $this->message = 'Some problems with registration';
                                        $this->code = 400;
                                    }
                                }
                            }
                        } else {
                            $this->message = 'Such login already registred';
                            $this->code = 400;
                        }
                    } else {
                        $this->message = 'Phone validation error';
                        $this->code = 400;
                    }
                } else*/ if (($authField === 'email') or is_null($authField)) {
                    $login = $userModel->getLoginByEmail($email);

                    if (!$userModel->checkUserIsset($login)) {
                        /*if (empty($code)) {
                            $code = SMSHelper::generateCode();

                            if (SMSHelper::saveCode($email, $code)) {
                                (new MailerController)->actionSendNewLetter($email, "Данные для авторизации", "Ваш пароль: {$code}. Вы можете изменить пароль после авторизации", 'letter');
                                
                                //(new MailerController)->actionSendNewUserNotification("Зарегистрирован новый пользователь", [
                                //    'email' => $email,
                                //]);

                                $this->message = 'Code had been sent';
                                $this->code = 200;
                            }
                        } else {*/
                            //if (SMSHelper::checkCode($email, $code)) {
                                if ($userModel->register((!empty($phone) ? $phone : $email), $password, $passwordRetype, $email, [
                                    'first_name' => $firstName,
                                    'last_name' => '',
                                    'middle_name' => '',
                                    'phone' => $phone,
                                    'mobilephone' => $phone,
                                ], intval($role) ? $role : UserRolesModel::ROLE_ID_USER)) {
                                    $this->message = 'Success registeration';
                                    $this->redirectUrl = App::init()->makeUrl('/{lk}/login');
                                    $this->code = 200;
                                } else {
                                    $this->message = 'Some problems with registration';
                                    $this->code = 400;
                                }
                            //}
                        //}
                    } else {
                        $this->message = 'Such login already registred';
                        $this->code = 400;
                    }
                }
            } else {
                $this->message = 'Registration / authorization method not selected ';
                $this->code = 400;
            }
        } else {
            $this->message = 'Allowed only POST-requests';
            $this->code = 400;
        }

        return $this->render(($this->userData ? $this->userData[0] : []), self::CODE_SUCCESS, ($this->code ? $this->code : 400), ($this->message ? $this->message : 'Error'));
    }

    /**
     * Метод восстановления пароля.
     *
     * @param void
     * @return void
     */
    public function actionRemindPassword()
    {
        global $modx;

        if (App::request()->isPostRequest()) {
            $authField = !in_array($modx->config['client_authField'], ['email', 'phone']) ? 'email' : $modx->config['client_authField'];

            $userModel = new WebUsersModel;

            $email = App::request()->extractPost('email', '');
            $phone = App::request()->extractPost('phone', '');
            $login = '';

            /*if (($authField === 'phone') and SMSHelper::validatePhone($phone)) {
                $login = intval($phone);

                if (!empty($login)) {
                    if ($userModel->checkUserIsset($login)) {
                        $userId = $userModel->getUserId($login);
                        $password = SMSHelper::generateCode();

                        if ($userModel->updatePass($userId, $password, $password)) {
                            if (SMSHelper::validatePhone($phone)) {
                                SMSHelper::sendMessage($phone, 'Ваш новый пароль от личного кабинета: ' . $password);
                            }

                            $this->message = 'Password succefully updated';
                            $this->redirectUrl = App::init()->makeUrl('/{lk}/login');
                            $this->code = 200;
                        } else {
                            $this->message = 'Some problems with password recovery';
                            $this->code = 400;
                        }
                    } else {
                        $this->message = 'Such login is not registred';
                        $this->code = 400;
                    }
                } else {
                    $this->code = 400;
                    $this->message = 'Login is empty';
                }
            } else */if ($authField === 'email') {
                $login = $userModel->getLoginByEmail($email);

                if (!empty($login)) {
                    $userId = $userModel->getUserId($login);
                    //$password = SMSHelper::generateCode();
                    $password = WebUsersModel::passGenerate();

                    if ($userModel->updatePass($userId, $password, $password)) {
                        if (MailerHelper::validateEmail($email)) {
                            (new MailerController)->actionSendNewPassword($userId, $password);
                        }

                        $this->message = 'Password succefully updated';
                        $this->redirectUrl = App::init()->makeUrl('/{lk}/login');
                        $this->code = 200;
                    } else {
                        $this->message = 'Some problems with password recovery';
                        $this->code = 400;
                    }
                } else {
                    $this->code = 400;
                    $this->message = 'Login is empty';
                }
            }
        } else {
            $this->message = 'Allowed only POST-requests';
            $this->code = 400;
        }

        return $this->render(($this->userData ? $this->userData[0] : []), self::CODE_SUCCESS, ($this->code ? $this->code : 400), ($this->message ? $this->message : 'Error'));
    }
}
