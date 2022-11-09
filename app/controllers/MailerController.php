<?php

namespace mmaurice\cabinet\controllers;

use \DateTime;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\controllers\MailControllerPrototype;
use mmaurice\cabinet\core\providers\ModxProvider;
use mmaurice\cabinet\helpers\DatesHelper;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\WebUsersModel;

class MailerController extends MailControllerPrototype
{
    /**
     * Main properties.
     *
     * @var string $layout
     * @var int|null $userId
     * @var string|null $orderNumber
     * @var array|null $tours
     */
    public $userId = null;
    public $orderNumber = null;
    public $tours = null;

    /**
     * Services properties.
     *
     * @var object
     */
    public $modx;

    /**
     * Required properties.
     * 
     * @var string $siteName
     * @var string $siteEmail
     * @var string $siteCompanyName
     * @var string $sitePhone
     */
    public $siteName = "";
    public $siteEmail = "";
    public $siteCompanyName = "";
    public $sitePhone = "";

    public function __construct()
    {
        ModxProvider::modxInit();

        $this->modx = ModxProvider::getModx();

        if (is_object($this->modx)) {
            $this->siteName = $this->modx->getConfig('client_siteName', "");
            $this->siteCompanyName = $this->modx->getConfig('client_siteCompanyName', "");
            $this->sitePhone = $this->modx->getConfig('client_sitePhone', "");
            $this->siteEmail = $this->modx->getConfig('client_siteEmail', "");
        }
    }

    public function actionSendUserAgencyRequest($userId = null)
    {
        if (is_null($userId)) {
            $this->userId = intval(App::request()->extractPost('userId', null));
        } else {
            $this->userId = $userId;
        }

        $userData = (new WebUsersModel)->getUserData($this->userId);

        if ($this->renderMail($this->siteEmail, "Поступил запрос на Агентство", 'changeUserTypeAgency', [
            'id' => $this->userId,
            'fullName' => $userData['attributes']['fullname'],
        ], true)) {
            return true;
        }
    }

    public function actionSendNewOrder($userId, $orderNumber)
    {
        $variables = [
            'orderNumber' => $this->orderNumber,
            'order' => OrdersModel::model()->getItem([
                'where' => [
                    "t.id = '{$orderNumber}'",
                ],
            ], true),
        ];

        $variables['tour'] = $variables['order']['tour'];

        if (!is_null($password)) {
            $variables['password'] = $password;
        }

        if ($userId) {
            $userData = (new WebUsersModel)->getUserData($this->userId);

            $variables['user'] = $userData;

            $currentDate = (new DateTime)->format('d-m-Y H:i');
            $variables['currentDate'] = $currentDate;
            $variables['siteInfo'] = [
                'siteName' => $this->siteName,
                'siteEmail' => $this->siteEmail,
                'siteCompanyName' => $this->siteCompanyName,
                'sitePhone' => $this->sitePhone

            ];

            if (!is_null($userData)) {
                if (!is_null($userData['attributes']['email']) and !empty($userData['attributes']['email'])) {
                    if ($this->renderMail($userData['attributes']['email'], "Номер Вашего заказа №{$this->orderNumber} от {$currentDate}", 'order', $variables, true)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function actionSendNewPassword($userId, $password)
    {
        if (!is_null($userId) or empty($userId) or !is_null($password) or empty($password)) {
            $userData = (new WebUsersModel)->getUserData($userId);

            if (!is_null($userData)) {
                if (!is_null($userData['attributes']['email']) and !empty($userData['attributes']['email'])) {
                    if ($this->renderMail($userData['attributes']['email'], "Востановление пароля от личного кабинета", 'restore', [
                        'login' => $userData['attributes']['email'],
                        'password' => $password,
                        'siteInfo' => [
                            'siteName' => $this->siteName,
                            'siteEmail' => $this->siteEmail,
                            'siteCompanyName' => $this->siteCompanyName,
                            'sitePhone' => $this->sitePhone,
                        ],
                    ])) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /*public function actionSendOrderPayment($orderId, array $variables)
    {
        if (is_array($variables) and !empty($variables)) {
            $order = OrdersModel::model()->getItem([
                'alias' => 'tor',
                'where' => [
                    "tor.id = '{$orderId}'",
                ],
            ], true);

            if ($order) {
                $variables['order'] = $order;

                $userData = (new WebUsersModel)->getUserData($userId);

                if ($this->renderMail($userData['attributes']['email'], "Оплата заказа", 'payment', $variables, true)) {
                    return true;
                }
            }
        }

        return false;
    }*/

    public function actionSendNewLetter($email, $subject, $text, $mailTemplate)
    {
        if (!empty($email) and !empty($text)) {
            if ($this->renderMail($email, $subject, $mailTemplate, [
                'content' => $text,
                'siteInfo' => [
                    'siteName' => $this->siteName,
                    'siteEmail' => $this->siteEmail,
                    'siteCompanyName' => $this->siteCompanyName,
                    'sitePhone' => $this->sitePhone
                ],
            ])) {
                return true;
            }
        }

        return false;
    }

    public function actionSendNewUserNotification($subject, $fields = [])
    {
        if ($this->renderMail($this->siteEmail, $subject, 'newUserNotify', $fields)) {
            return true;
        }

        return false;
    }

    public function actionSendMessageNotification($email, $order)
    {
        if (!empty($email) and !empty($text)) {
            if ($this->renderMail($email, $subject, $mailTemplate, [
                'content' => $text,
                'siteInfo' => [
                    'siteName' => $this->siteName,
                    'siteEmail' => $this->siteEmail,
                    'siteCompanyName' => $this->siteCompanyName,
                    'sitePhone' => $this->sitePhone
                ],
            ])) {
                return true;
            }
        }

        return false;
    }
}
