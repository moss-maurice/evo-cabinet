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
            // Ищем пользователя по приоритетному полю
            $userModel = new WebUsersModel;

            $tourId = App::request()->extractPost('tourId');
            $userId = App::request()->extractPost('userId', $userModel->isLogged() ? $userModel->getId() : null);
            $price = App::request()->extractPost('price');
            $comment = App::request()->extractPost('comment');

            if ($userId) {
                if ($orderId = OrdersModel::model()->setOrder($tourId, $userId, $price, $comment)) {
                    return $this->render([
                        'orderId' => $orderId,
                        'link' => App::init()->makeUrl('/{lk}/order', [
                            'orderId' => $orderId,
                        ]),
                    ]);
                }
            }
        }

        return $this->render([
            'error' => true,
        ]);
    }
}
