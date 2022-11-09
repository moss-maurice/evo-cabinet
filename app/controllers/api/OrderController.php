<?php

namespace mmaurice\cabinet\controllers\api;

use mmaurice\cabinet\components\ApiControllerComponent;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\WebUsersModel;

class OrderController extends ApiControllerComponent
{
    public function beforeAction($action)
    {
        //parent::beforeAction($action);

        if (!in_array($action, ['actionIndex'])) {
            $this->checkAuth([
                'redirect' => App::init()->makeUrl($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/{lk}/login/'),
            ]);
        }
    }

    public function actionIndex()
    {
        if (app::request()->isPostRequest()) {
            $tourId = App::request()->extractPost('tour_id');
            $userId = App::request()->extractPost('user_id', $this->autoUserCheck());
            $price = App::request()->extractPost('price');
            $comment = App::request()->extractPost('comment');
            $fields = App::request()->extractPost('fields', []);

            if ($orderId = OrdersModel::model()->setOrder($tourId, $userId, $price, $comment, $fields)) {
                return $this->render([
                    'orderId' => $orderId
                ]);
            }
        }

        return $this->render([
            'error' => true,
        ]);
    }

    protected function autoUserCheck()
    {
        global $modx;

        $userId = App::request()->extractPost('user_id');
        $user = App::request()->extractPost('user');

        // Ищем пользователя по приоритетному полю
        $userModel = new WebUsersModel;

        // Проверим текущую авторизацию
        if ($userModel->isLogged()) {
            // Если мы каким-то чудом уже авторизировались
            $userId = $userModel->getId();
        } else {
            // В противном случае, нам надо что-то с этим делать

            if (is_null($userId)) {
                // Получаем в настройках modx опцию приоритетного поля регистрации
                $authField = !in_array($modx->config['client_authField'], ['email', 'phone']) ? 'email' : $modx->config['client_authField'];

                switch ($authField) {
                    case 'phone':
                        $userId = $userModel->getUserIdByPhone($userPhone);
                        break;
                    case 'email':
                        $userId = $userModel->getUserIdByEmail($userEmail);
                        break;
                }
            }

            // Сгенерируем поля пользователя
            $fields = [
                'fullname' => "{$user['last_name']} {$user['first_name']}",
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'phone' => $user['phone'],
                'city' => $user['city'],
            ];

            // Если найден
            if (!is_null($userId)) {
                // Авторизация
                $userModel->loadUserData($userId);

                // Обновим профиль юзера
                $userModel->updateProfile($userId, $fields);
            } else {
                // Иначе генерируем пароль
                $password = $userModel->passGenerate();

                // Регистрируемся + автологинимся
                $userModel->register($userEmail, $password, $password, $userEmail, $fields, false, true);

                if ($userModel->isLogged()) {
                    $userId = $userModel->getId();
                }
            }
        }

        // Ещё раз проверим текущую авторизацию
        if ($userModel->isLogged()) {
            return $userId;
        }

        return $this->checkAuth([
            'redirect' => App::init()->makeUrl($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/{lk}/login/'),
        ]);
    }
}
