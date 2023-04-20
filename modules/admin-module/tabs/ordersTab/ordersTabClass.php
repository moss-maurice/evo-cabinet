<?php

use mmaurice\cabinet\controllers\MailerController;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\helpers\ApiHelper;
use mmaurice\cabinet\helpers\FileHelper;
use mmaurice\cabinet\models\DocumentsDocxExcursionsFrontModel;
use mmaurice\cabinet\models\DocumentsDocxToursFrontModel;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\OrdersPaymentsTransactionsModel;
use mmaurice\cabinet\models\OrdersPersonsModel;
use mmaurice\cabinet\models\OrdersPlacesModel;
use mmaurice\cabinet\models\OrdersStatusesModel;
use mmaurice\cabinet\models\SiteContentExcursionsModel;
use mmaurice\cabinet\models\SiteContentModel;
use mmaurice\cabinet\models\SiteContentObjectsModel;
use mmaurice\cabinet\models\SiteContentToursModel;
use mmaurice\cabinet\models\TransactionsTypesModel;
use mmaurice\cabinet\models\WebUserAttributesModel;
use mmaurice\cabinet\models\WebUserSettingsModel;
use mmaurice\cabinet\models\WebUserThreadFilesModel;
use mmaurice\cabinet\models\WebUserThreadMessagesModel;
use mmaurice\cabinet\models\WebUserThreadsModel;

class OrdersTabClass extends TabClass
{
    public $title = 'Заявки';
    public $description = 'Заявки с сайта, доступные в ЛК.';
    public $orderPosition = 10;

    public function actionIndex()
    {
        return $this->actionList();
    }

    public function actionList()
    {
        $page = intval(App::request()->extractPost('page', 1));
        $limit = intval(App::request()->extractPost('limit', 25));

        if ($page === 0) {
            $limit = null;
            $page = 1;
        }

        $fields = [
            'id' => App::request()->extractPost('item_id'),
            'status' => intval(App::request()->extractPost('status', OrdersStatusesModel::STATUS_ALL)),
            'tour' => array_filter(App::request()->extractPost('tour', [])),
            'login' => trim(App::request()->extractPost('login')),
            'email' => trim(App::request()->extractPost('email')),
            'phone' => trim(App::request()->extractPost('phone')),
            'field' => App::request()->extractPost('field'),
        ];

        $ordersList = OrdersModel::model()->getOrdersListByPagination($page, $limit, $fields);

        return $this->render('list', array_merge([
            'tabName' => trim(App::request()->extractPost('tabName', $this->tabName)),
            'tabMethod' => trim(App::request()->extractPost('method', 'index')),
            'ordersList' => $ordersList['ordersList'],
            'statusList' => OrdersStatusesModel::model()->getList([
                    'order' => [
                        "t.position ASC",
                    ],
                ]),
            'pages' => $ordersList['pages'],
            'page' => $page,
            'limit' => $ordersList['limit'],
            'directions' => [
                App::request()->extractPost('field', 'tourDate') => App::request()->extractPost('direction', 'DESC')
            ],
        ], $fields));
    }

    public function actionMails()
    {
        $itemId = App::request()->extractPost('item_id');

        if ($itemId) {
            $itemId = intval($itemId);

            return $this->render('mails', [
                'itemId' => $itemId,
                'tabName' => App::request()->extractPost('tabName', $this->tabName),
            ]);
        }

        return $this->actionList();
    }

    public function actionSendNewOrder()
    {
        $itemId = App::request()->extractPost('item_id');

        if ($itemId) {
            $itemId = intval($itemId);

            $order = OrdersModel::model()->getItem([
                'where' => [
                    "t.id = '{$itemId}'",
                ],
            ], ['user', 'payments.*', 'status', 'thread', 'tour', 'properties']);

            $userId = intval($order['user']['id']);
            $orderNumber = intval($order['id']);
            $tours = $order['tour'];

            (new MailerController)->actionSendNewOrder($userId, $orderNumber, $tours);
        }

        return $this->actionMails();
    }

    public function actionView($message = null)
    {
        $itemId = App::request()->extractPost('item_id');

        if ($itemId) {
            $itemId = intval($itemId);

            $order = OrdersModel::model()->getItem([
                'where' => [
                    "t.id = '{$itemId}'",
                ],
            ], ['user', 'payments.*', 'status', 'thread', 'tour', 'properties']);

            $transactions = TransactionsTypesModel::model()->getList([
                'where' => [
                    "alias !='" . TransactionsTypesModel::TRANSACTION_TEMP . "'",
                ],
            ]);

            return $this->render('view', [
                'itemId' => $itemId,
                'order' => $order,
                'tabName' => App::request()->extractPost('tabName', $this->tabName),
                'statuses' => OrdersStatusesModel::model()->getList([
                        'order' => [
                            "t.position ASC",
                        ],
                    ]),
                'payments' => OrdersPaymentsTransactionsModel::model()->getPaymentsByOrderId($id),
                'transactions' => $transactions,
                'message' => $message,
                'errors' => $errors,
            ]);
        }

        return $this->actionList();
    }

    public function actionUpdate()
    {
        $orderId = App::request()->extractPost('item_id');
        $statusId = App::request()->extractPost('status_id');
        $roomNumber = App::request()->extractPost('roomNumber');

        if ($orderId) {
            $orderId = intval($orderId);

            $order = OrdersModel::model()->getItem([
                'where' => [
                    "id = '{$orderId}'"
                ],
            ]);

            OrdersModel::model()->update([
                'status_id' => intval($statusId),
            ], "id = '{$orderId}'");

            if (intval($order['status_id']) !== intval($statusId)) {
                $status = OrdersStatusesModel::model()->getItem([
                    'where' => [
                        "id = '{$statusId}'",
                    ],
                ]);

                $thread = WebUserThreadsModel::model()->getOrderThread($orderId);

                if (!$thread) {
                    WebUserThreadsModel::model()->addThread($orderId, 'Общение с менеджером', $order['user_id']);

                    $thread = WebUserThreadsModel::model()->getOrderThread($orderId);
                }

                WebUserThreadMessagesModel::model()->addStatus($thread['id'], "Статус заявки изменен на \'{$status['name']}\'", 0);

                // Для архивных заявков (id = 7) и отмененных заявок (id = 3) мы удаляем привязку мест туристов к схемам автобусов
                if (in_array(intval($status['id']), [OrdersStatusesModel::STATUS_ARCHIVE, OrdersStatusesModel::STATUS_CANCELED])) {
                    if (OrdersPlacesModel::model()->delete("order_id = '{$orderId}'")) {
                        WebUserThreadMessagesModel::model()->addStatus($thread['id'], "Отменена бронь мест в транспорте", 0);
                    }
                }

                if (intval($orderId)) {
                    $userAttrData = WebUserAttributesModel::model()->getDataByOrderId($orderId);

                    if (is_array($userAttrData) and !empty($userAttrData)) {
                        $userAttrData = array_shift($userAttrData);

                        if (!empty($userAttrData['email']) and !is_null($userAttrData['email'])) {
                            (new MailerController)->actionSendNewLetter($userAttrData['email'], 'Изменение статуса заявки', "Статус заявки изменен на \'{$status['name']}\'", 'letter');
                            (new MailerController)->actionSendNewUserNotification("Зарегистрирован новый пользователь", [
                                'email' => $userAttrData['email'],
                            ]);
                        }
                    }
                }
            }

            if ($roomNumber) {
                $roomNumber = htmlspecialchars($roomNumber);

                OrdersModel::model()->update([
                    'room_number' => $roomNumber
                ], "id = '{$orderId}'");
            }

            OrdersPaymentsTransactionsModel::applyOrderPersonalSale($orderId);

            return $this->actionView();
        }

        return $this->actionList();
    }

    public function actionRemove()
    {
        $orderId = App::request()->extractPost('orderId');

        if ($orderId) {
            $orderId = intval($orderId);

            OrdersModel::model()->update([
                'status_id' => OrdersStatusesModel::STATUS_DELETED,
            ], "id = '{$orderId}'");
        }

        return $this->actionList();
    }

    public function actionTouristView()
    {
        $itemId = App::request()->extractPost('item_id');

        if ($itemId) {
            return $this->render('view_tourist', [
                'itemId' => intval($itemId),
                'tabName' => App::request()->extractPost('tabName', $this->tabName),
                'tourist' => OrdersPersonsModel::model()->getItem([
                    'where' => [
                        "t.id = '{$itemId}'",
                    ],
                ]),
            ]);
        }

        return $this->actionList();
    }

    public function actionTouristUpdate()
    {
        $itemId = App::request()->extractPost('item_id');

        if ($itemId) {
            $itemId = intval($itemId);

            OrdersPersonsModel::model()->update([
                'surname' => App::request()->extractPost('surname'),
                'name' => App::request()->extractPost('name'),
                'middlename' => App::request()->extractPost('middlename'),
                'date' => App::request()->extractPost('date'),
                'document' => App::request()->extractPost('document'),
                'email' => App::request()->extractPost('email'),
                'phone' => App::request()->extractPost('phone'),
                'comment' => App::request()->extractPost('comment'),
            ], "id = '{$itemId}'");

            return $this->actionTouristView();
        }

        return $this->actionList();
    }

    public function actionAddMessage()
    {
        $orderId = App::request()->extractPost('item_id');
        $message = App::request()->extractPost('message', '');
        $files = App::request()->extractPost('files', []);
        $replyToMessageId = App::request()->extractPost('replyToMessageId', 0);

        if ($orderId) {
            $thread = WebUserThreadsModel::model()->getOrderThread($orderId);

            $messageId = WebUserThreadMessagesModel::model()->addMessage($thread['id'], $message, 0, $replyToMessageId);

            WebUserThreadFilesModel::model()->addMessageFiles($messageId, $files);
        }

        return $this->actionView();
    }

    public function actionUpdateMessage()
    {
        $message = App::request()->extractPost('message', '');
        $messageId = App::request()->extractPost('messageId');
        $senderId = App::request()->extractPost('senderId', 0);
        $replyToMessageId = App::request()->extractPost('replyToMessageId');
        $files = App::request()->extractPost('files', []);

        if ($messageId) {
            WebUserThreadMessagesModel::model()->updateMessage($messageId, $message, $senderId, $replyToMessageId);

            WebUserThreadFilesModel::model()->updateMessageFiles($messageId, $files);
        }

        return $this->actionView();
    }

    public function actionDeleteMessage()
    {
        $messageId = App::request()->extractPost('messageId');

        WebUserThreadMessagesModel::model()->deleteMessage($messageId);

        return $this->actionView();
    }

    public function actionUpdateTourOrderPrice()
    {
        $orderId = App::request()->extractPost('orderId');
        $price = App::request()->extractPost('price');

        if ($orderId and $price) {
            OrdersModel::model()->updateTourOrderPrice($orderId, $price);
        }
    }

    public function actionAddOrderPayment()
    {
        $orderId = App::request()->extractPost('item_id');
        $comment = App::request()->extractPost('comment', '');
        $transactionValue = floatval(App::request()->extractPost('transactionValue'));
        $transactionType = App::request()->extractPost('transactionType', TransactionsTypesModel::TRANSACTION_PAYMENT_ID);

        if ($orderId and $transactionValue and $transactionType) {
            $orderId = intval($orderId);
            $transactionType = intval($transactionType);

            OrdersPaymentsTransactionsModel::model()->insert([
                'transaction_value' => $transactionValue,
                'transaction_type' => $transactionType,
                'comment' => $comment,
                'order_id' => $orderId,
            ]);

            $order = OrdersModel::model()->getItem([
                'where' => [
                    "id = '{$orderId}'"
                ],
            ]);

            $thread = WebUserThreadsModel::model()->getOrderThread($orderId);

            if (!$thread) {
                WebUserThreadsModel::model()->addThread($orderId, 'Общение с менеджером', $order['user_id']);

                $thread = WebUserThreadsModel::model()->getOrderThread($orderId);
            }

            switch ($transactionType) {
                case TransactionsTypesModel::TRANSACTION_DISCOUNT_ID:
                    WebUserThreadMessagesModel::model()->addStatus($thread['id'], "Применена скидка в размере {$transactionValue} ₽" . (!empty($comment) ? " с коментарием \'{$comment}\'" : ''), 0);

                    break;
                case TransactionsTypesModel::TRANSACTION_MARGIN_ID:
                    WebUserThreadMessagesModel::model()->addStatus($thread['id'], "Стоимость заявки увеличена на {$transactionValue} ₽" . (!empty($comment) ? " с коментарием \'{$comment}\'" : ''), 0);

                    break;
                case TransactionsTypesModel::TRANSACTION_PAYMENT_ID:
                default:
                    WebUserThreadMessagesModel::model()->addStatus($thread['id'], "Зачислена оплата в размере {$transactionValue} ₽" . (!empty($comment) ? " с коментарием \'{$comment}\'" : ''), 0);

                    break;
            }
        }

        return $this->actionView();
    }

    public function actionDeleteOrderPayment()
    {
        $itemId = App::request()->extractPost('paymentId');

        if ($itemId) {
            $itemId = intval($itemId);

            OrdersPaymentsTransactionsModel::model()->update([
                'deleted' => OrdersPaymentsTransactionsModel::DELETED,
            ], "id = '{$itemId}'");
        }

        return $this->actionView();
    }

    public function actionUpdateOrderPayment()
    {
        $orderId = App::request()->extractPost('orderId');
        $paymentId = App::request()->extractPost('paymentId');
        $comment = App::request()->extractPost('comment');
        $transactionValue = App::request()->extractPost('transactionValue');
        $transactionType = App::request()->extractPost('transactionType');

        if ($orderId and $paymentId and $comment and $transactionValue and $transactionType) {
            if ($OrdersPaymentsTransactionsModel::model()->updateAdminPaymentTransaction($orderId, $paymentId, $comment, $transactionValue, $transactionType)) {
                return true;
            }
        }

        return false;
    }

    public function actionGetOrderById()
    {
        $orderId = App::request()->extractPost('orderId');

        if ($orderId) {
            $orderId = intval($orderId);

            $payments = OrdersPaymentsTransactionsModel::model()->getPaymentsByOrderId($orderId);
            $order = OrdersModel::model()->getOrderById($orderId);

            if ($order and $payments) {
                $order['payments'] = $payments;
            }

            return $order;
        }

        return false;
    }

    public function actionSaveTouristHotelPlace()
    {
        $touristId = App::request()->extractPost('touristId');
        $touristHotelRoom = App::request()->extractPost('touristHotelRoom');

        if ($touristId and $touristHotelRoom) {
            $touristId = intval($touristId);
            $touristHotelRoom = htmlspecialchars($touristHotelRoom);

            OrdersPersonsModel::model()->update([
                'room_number' => $touristHotelRoom
            ], "id = '{$touristId}'");
        }

        return $this->actionView();
    }

    public function actionSaveTouristBusPlace()
    {
        return $this->actionView((!ApiHelper::changePlace() ? 'Не удалось обновить место туриста в автобусе' : null));
    }

    public function actionSearchByUserId()
    {
        $page = intval(App::request()->extractPost('page', 1));
        $limit = intval(App::request()->extractPost('limit', 25));
        $dataId = intval(App::request()->extractPost('dataId'));

        $pages = 0;
        $ordersList = [];

        if ($dataId) {
            $dataId = intval($dataId);

            $ordersList = OrdersModel::model()->getOrdersByUserId($dataId, $page, $limit);

            $pages = $ordersList['pages'];
            $ordersList = $ordersList['ordersList'];
        }

        return $this->render('list', [
            'tabName' => App::request()->extractPost('tabName', $this->tabName),
            'tabMethod' => App::request()->extractPost('method', 'index'),
            'dataId' => $dataId,
            'ordersList' => $ordersList,
            'pages' => $pages,
            'page' => $page
        ]);
    }

    public function actionSearchBySiteContentId()
    {
        $page = intval(App::request()->extractPost('page', 1));
        $limit = intval(App::request()->extractPost('limit', 25));
        $dataId = intval(App::request()->extractPost('dataId'));

        $pages = 0;
        $ordersList = [];

        if ($dataId) {
            $dataId = intval($dataId);

            $ordersList = OrdersModel::model()->getOrdersByTourSiteContentId($dataId, $page, $limit);

            $pages = $ordersList['pages'];
            $ordersList = $ordersList['ordersList'];
        }

        return $this->render('list', array(
            'tabName' => App::request()->extractPost('tabName', $this->tabName),
            'tabMethod' => App::request()->extractPost('method', 'index'),
            'dataId' => $dataId,
            'ordersList' => $ordersList,
            'pages' => $pages,
            'page' => $page
        ));
    }

    public function actionAgencyAutocomplete()
    {
        $text = intval(App::request()->extractPost('text'));

        if ($text) {
            return WebUserSettingsModel::model()->getAgencyAutocomplete($text);
        }

        return false;
    }

    public function actionClientAutocomplete()
    {
        $text = intval(App::request()->extractPost('text'));

        if ($text) {
            return WebUserSettingsModel::model()->getClientAutocomplete($text);
        }

        return false;
    }

    public function actionTourAutocomplete()
    {
        $text = intval(App::request()->extractPost('text'));

        if ($text) {
            return SiteContentToursModel::model()->getTourAutocomplete($text);
        }

        return false;
    }

    public function actionFileUpload()
    {
        $from = $_FILES['file']['tmp_name'];
        $to = realpath(dirname(__FILE__) . "/../../../../runtimes") . "/uploads/" . md5(pathinfo($_FILES['file']['name'], PATHINFO_FILENAME) . "_" . $_SERVE['REMOTE_ADDR'] . "_" . time()) . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if (FileHelper::moveFile($from, $to)) {
            if ($info = FileHelper::fileInfo($to)) {
                $info['name'] = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);

                return $this->renderJson($info);
            }
        }

        return $this->renderJson(false);
    }
}
