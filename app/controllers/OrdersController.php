<?php

namespace mmaurice\cabinet\controllers;

use mmaurice\cabinet\components\ControllerComponent;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\WebUserThreadMessagesModel;
use mmaurice\cabinet\models\WebUserThreadsModel;
use mmaurice\cabinet\models\WebUsersModel;

class OrdersController extends ControllerComponent
{
    public function actionIndex()
    {
        $this->checkAccess('/{lk}/login/');
        $this->checkRole('all', '/login');

        $userId = WebUsersModel::model()->getId();

        $pagination = OrdersModel::model()->getOrdersListByPagination(App::request()->extractGet('page', 1), null, [
            'user_id' => $userId,
        ]);

        $this->render('index', [
            'pagination' => $pagination,
        ]);
    }

    public function actionView()
    {
        $this->checkAccess('/{lk}/login/');
        $this->checkRole('all', '/login');

        $orderId = App::request()->extractGet('orderId');
        $userId = WebUsersModel::model()->getId();

        $order = OrdersModel::model()->getItem([
            'where' => [
                "t.id = '{$orderId}'",
                "AND user_id = '{$userId}'",
            ],
        ], true);

        $this->render('view', [
            'order' => $order,
        ]);
    }

    public function actionAddMessage()
    {
        $this->checkAccess('/{lk}/login/');
        $this->checkRole('all', '/login');

        $orderId = App::request()->extractPost('orderId');
        $threadId = App::request()->extractPost('threadId');
        $userId = WebUsersModel::model()->getId();
        $message = App::request()->extractPost('messageText');

        $thread = WebUserThreadsModel::model()->getItem([
            'where' => [
                "t.id = '{$threadId}'",
                "AND t.order_id = '{$orderId}'",
                "AND t.webuser = '{$userId}'",
            ],
        ], true);

        if ($thread) {
            $message = WebUserThreadMessagesModel::model()->addMessage($threadId, $message, $userId);
        }

        App::init()->redirect('/{lk}/order', [
            'orderId' => $orderId,
        ]);
    }
}
