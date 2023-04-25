<?php

namespace mmaurice\cabinet\controllers;

use mmaurice\cabinet\components\ControllerComponent;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\OrdersStatusesModel;
use mmaurice\cabinet\models\SitePlugins;
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

        $pagination = OrdersModel::model()->getOrdersListByPagination(App::request()->extractGet('page', 1), 10, [
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
        ], ['user', 'payments.*', 'status', 'thread', 'tour', 'properties']);

        $thread = WebUserThreadsModel::model()->getItem([
            'where' => [
                "t.order_id = '{$orderId}'",
                "AND t.webuser = '{$userId}'",
            ],
        ], true);

        $statuses = OrdersStatusesModel::model()->getList([
            'where' => [
                't.public = 1',
            ],
            'order' => [
                't.position',
            ],
        ]);

        $paymentsPlugins = SitePlugins::model()->getList([
            'where' => [
                "t.name IN ('" . implode("', '", ['Sberbank Payment', ]) . "')",
                "AND t.disabled = '0'",
            ],
        ]);

        $this->render('view', [
            'order' => $order,
            'thread' => $thread,
            'statuses' => $statuses,
            'paymentsPlugins' => $paymentsPlugins,
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
        $replyTo = App::request()->extractPost('replyTo');
        $messageId = App::request()->extractPost('messageId');
        $removeId = App::request()->extractPost('removeId');

        $thread = null;

        if ($threadId) {
            $thread = WebUserThreadsModel::model()->getItem([
                'where' => [
                    "t.id = '{$threadId}'",
                    "AND t.order_id = '{$orderId}'",
                    "AND t.webuser = '{$userId}'",
                ],
            ], true);
        }

        if (!$thread) {
            $threadId = WebUserThreadsModel::model()->addThread($orderId, 'Общение с менеджером', $userId);

            $thread = WebUserThreadsModel::model()->getItem([
                'where' => [
                    "t.id = '{$threadId}'",
                    "AND t.order_id = '{$orderId}'",
                    "AND t.webuser = '{$userId}'",
                ],
            ], true);
        }

        if ($thread) {
            if ($messageId) {
                $checkMessage = WebUserThreadMessagesModel::model()->getItem([
                    'where' => [
                        "t.id = '{$messageId}'",
                        "AND t.sender = '{$userId}'",
                    ],
                ]);

                if ($checkMessage) {
                    if ($removeId and ($messageId === $removeId)) {
                        $message = WebUserThreadMessagesModel::model()->deleteMessage($messageId);
                    } else {
                        $message = WebUserThreadMessagesModel::model()->updateMessage($messageId, $message, $userId, $replyTo);
                    }
                }
            } else {
                $message = WebUserThreadMessagesModel::model()->addMessage($threadId, $message, $userId, $replyTo);
            }
        }

        App::init()->redirect('/{lk}/order', [
            'orderId' => $orderId,
        ]);
    }
}
