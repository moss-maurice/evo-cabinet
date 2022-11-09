<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\controllers\MailerController;
use mmaurice\cabinet\core\helpers\FormatHelper;
use mmaurice\cabinet\helpers\DatesHelper;
use mmaurice\cabinet\helpers\TourHelper;
use mmaurice\cabinet\models\Model;
use mmaurice\cabinet\models\OrdersPaymentsTransactionsModel;
use mmaurice\cabinet\models\OrdersPropertiesModel;
use mmaurice\cabinet\models\OrdersStatusesModel;
use mmaurice\cabinet\models\SiteContentModel;
use mmaurice\cabinet\models\SiteContentToursModel;
use mmaurice\cabinet\models\TransactionsTypesModel;
use mmaurice\cabinet\models\WebUserThreadMessagesModel;
use mmaurice\cabinet\models\WebUserThreadsModel;
use mmaurice\cabinet\models\WebUsersModel;

class OrdersModel extends Model
{
    public $tableName = 'orders';
    public $relations = [
        'user' => ['user_id', [WebUsersModel::class, 'id'], self::REL_ONE],
        'payments' => ['id', [OrdersPaymentsTransactionsModel::class, 'order_id'], self::REL_MANY],
        'status' => ['status_id', [OrdersStatusesModel::class, 'id'], self::REL_ONE],
        'thread' => ['id', [WebUserThreadsModel::class, 'order_id'], self::REL_ONE],
        'tour' => ['tour_id', [SiteContentModel::class, 'id'], self::REL_ONE],
        'properties' => ['id', [OrdersPropertiesModel::class, 'order_id'], self::REL_MANY],
    ];

    public function getOrderById($id)
    {
        $id = intval($id);

        $order = $this->getItem([
            'where' => [
                "t.id = '{$id}'",
            ],
        ], true);

        return $order;
    }

    public static function getProperties($orderId)
    {
        $properties = OrdersPropertiesModel::model()->getList([
            'where' => [
                "t.order_id = '{$orderId}'",
            ]
        ]);

        if ($properties) {
            return OrdersPropertiesModel::propertiesPrepare($properties);
        }

        return [];
    }

    // Базовая стоимость заказа
    public function getOrderBasePrice($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $order = $this->getItem([
            'select' => [
                "t.price",
            ],
            'where' => [
                "t.id = '{$orderId}'",
            ],
        ]);

        if ($order) {
            return number_format(floatval($order['price']), $decimals, $decPoint, $thousandsSep);
        }

        return number_format(0, $decimals, $decPoint, $thousandsSep);
    }

    // Размер штрафов и скидок
    public function getOrderModPayments($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $sanctions = $this->getOrderSanctionPayments($orderId, $decimals, $decPoint, $thousandsSep);
        $sales = $this->getOrderSales($orderId, $decimals, $decPoint, $thousandsSep);

        return number_format(floatval($sanctions + $sales), $decimals, $decPoint, $thousandsSep);
    }

    // Размер штрафов
    public function getOrderSanctionPayments($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $price = 0;

        $transactions = OrdersPaymentsTransactionsModel::model()->getList([
            'select' => [
                "transaction_value",
            ],
            'where' => [
                "t.transaction_type IN ('" . implode("', '", [TransactionsTypesModel::EDITED_BY_ADMIN_ID]) . "')",
                "AND t.deleted = '0'",
                "AND t.order_id = '" . $orderId . "'",
                "AND t.transaction_value > '0'"
            ],
        ]);

        if ($transactions) {
            foreach ($transactions as $transaction) {
                $price += floatval($transaction['transaction_value']);
            }
        }

        return number_format(floatval($price), $decimals, $decPoint, $thousandsSep);
    }

    // Размер скидок
    public function getOrderSalesPayments($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $price = 0;

        $transactions = OrdersPaymentsTransactionsModel::model()->getList([
            'select' => [
                "transaction_value",
            ],
            'where' => [
                "t.transaction_type IN ('" . implode("', '", [TransactionsTypesModel::EDITED_BY_ADMIN_ID]) . "')",
                "AND t.deleted = '0'",
                "AND t.order_id = '" . $orderId . "'",
                "AND t.transaction_value < '0'"
            ],
        ]);

        if ($transactions) {
            foreach ($transactions as $transaction) {
                $price += floatval($transaction['transaction_value']);
            }
        }

        return number_format(floatval($price), $decimals, $decPoint, $thousandsSep);
    }

    // Размер персональной скидки
    public function getOrderUserSales($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $price = 0;

        $transactions = OrdersPaymentsTransactionsModel::model()->getList([
            'select' => [
                "transaction_value",
            ],
            'where' => [
                "t.transaction_type IN ('" . implode("', '", [TransactionsTypesModel::PERSONAL_SALE_ID]) . "')",
                "AND t.deleted = '0'",
                "AND t.order_id = '" . $orderId . "'",
            ],
        ]);

        if ($transactions) {
            foreach ($transactions as $transaction) {
                $price += floatval($transaction['transaction_value']);
            }
        }

        return number_format(floatval(0 - abs($price)), $decimals, $decPoint, $thousandsSep);
    }

    // Общий размер скидок
    public function getOrderSales($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $paymentsSales = $this->getOrderSalesPayments($orderId, $decimals, $decPoint, $thousandsSep);
        $userSales = $this->getOrderUserSales($orderId, $decimals, $decPoint, $thousandsSep);

        return number_format(floatval(0 - (abs($paymentsSales) + abs($userSales))), $decimals, $decPoint, $thousandsSep);
    }

    // Размер фактических оплат
    public function getOrderTotalPayments($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $price = 0;

        $transactions = OrdersPaymentsTransactionsModel::model()->getList([
            'select' => [
                "transaction_value",
            ],
            'where' => [
                "t.transaction_type IN ('" . implode("', '", [TransactionsTypesModel::INTERNET_PAYMENT, TransactionsTypesModel::TRANSACTION_TO_CARD, TransactionsTypesModel::CASH_TO_BANK_BY_PDF]) . "')",
                "AND t.deleted = '0'",
                "AND t.order_id = '" . $orderId . "'",
                "AND t.transaction_value > '0'",
            ],
        ]);

        if ($transactions) {
            foreach ($transactions as $transaction) {
                $price += floatval($transaction['transaction_value']);
            }
        }

        return number_format(floatval($price), $decimals, $decPoint, $thousandsSep);
    }

    // Cтоимость заказа с учётом штрафов и скидок
    public function getOrderPrice($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $price = floatval($this->getOrderBasePrice($orderId));
        $modPayments = floatval($this->getOrderModPayments($orderId));

        return number_format(floatval(abs($price) - abs($modPayments)), $decimals, $decPoint, $thousandsSep);
    }

    // Размер остатка оплат
    public function getOrderBalancePayments($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $price = floatval($this->getOrderPrice($orderId));
        $totalPayments = floatval($this->getOrderTotalPayments($orderId));

        return number_format(floatval($price - $totalPayments), $decimals, $decPoint, $thousandsSep);
    }

    // Сумма оплат
    public function getOrderPayments($orderId, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $modPayments = floatval($this->getOrderModPayments($orderId));
        $totalPayments = floatval($this->getOrderTotalPayments($orderId));

        return number_format(floatval($modPayments + $totalPayments), $decimals, $decPoint, $thousandsSep);
    }

    protected static function extractMultipleValue($value)
    {
        $value = str_replace(',', ';', trim(strval($value)));

        $array = explode(';', $value);

        if (is_array($array) and !empty($array)) {
            return $array;
        }

        return [];
    }

    public function getOrdersListByPagination($page, $limit = 25, $filter = [])
    {
        $page = intval($page);

        if (!is_null($limit)) {
            $limit = intval($limit);
        }

        $join = [];
        $where = [
            '1 = 1',
        ];
        $order = [];

        if (is_array($filter) and !empty($filter)) {
            if (array_key_exists('status', $filter)) {
                $status = intval($filter['status']);

                $blackList = [
                    OrdersStatusesModel::STATUS_ARCHIVE,
                    OrdersStatusesModel::STATUS_DELETED,
                ];

                $whiteList = [
                    $status,
                ];

                if (is_null($status) or empty($status) or ($status == 0)) {
                    $where = array_merge($where, [
                        "AND t.status_id NOT IN ('" . implode("', '", $blackList) . "')",
                    ]);
                } else {
                    $where = array_merge($where, ["AND t.status_id IN ('" . implode("', '", $whiteList) . "')",
                ]);
                }
            }

            if (array_key_exists('id', $filter) and !empty($filter['id'])) {
                $filter['id'] = self::extractMultipleValue($filter['id']);

                if (is_array($filter['id']) and !empty($filter['id'])) {
                    $where[] = "AND t.id IN ('" . implode("','", $filter['id']) . "')";
                }
            }

            if (array_key_exists('user_id', $filter) and !empty($filter['user_id'])) {
                $where[] = "AND t.user_id ='{$filter['user_id']}'";
            }

            if ((array_key_exists('tour', $filter) and !empty($filter['tour'])) OR (array_key_exists('tourVoyageOutDate', $filter) and !empty($filter['tourVoyageOutDate'])) OR (array_key_exists('tourVoyageInDate', $filter) and !empty($filter['tourVoyageInDate']))) {
                $join = array_merge($join, [
                    "LEFT JOIN " . ToursVoyagesModel::getFullModelTableName() . " tv ON tv.id = t.voyage_id",
                ]);

                if (array_key_exists('tour', $filter) and !empty($filter['tour'])) {
                    $join = array_merge($join, [
                        "LEFT JOIN " . SiteContentToursModel::getFullModelTableName() . " sc ON sc.id = t.tour_id OR sc.id = tv.site_content_id",
                    ]);

                    /*$where = array_merge($where, [
                        "AND sc.id IS NOT NULL",
                        "AND sc.pagetitle LIKE '%" . trim($filter['tour']) . "%'",
                    ]);*/
                    $where = array_merge($where, [
                        "AND sc.id IN ('" . implode("', '", $filter['tour']) . "')",
                    ]);
                }

                if (array_key_exists('tourVoyageOutDate', $filter) and !empty($filter['tourVoyageOutDate'])) {
                    $join = array_merge($join, [
                        "LEFT JOIN " . VoyagesModel::getFullModelTableName() . " v ON v.id = tv.voyage_out_id",
                    ]);

                    $where = array_merge($where, [
                        "AND DATE_FORMAT(v.date, '%Y-%m-%d') = STR_TO_DATE('" . $filter['tourVoyageOutDate'] . "', '%d-%m-%Y')",
                    ]);
                }

                if (array_key_exists('tourVoyageInDate', $filter) and !empty($filter['tourVoyageInDate'])) {
                    $join = array_merge($join, [
                        "LEFT JOIN " . VoyagesModel::getFullModelTableName() . " v ON v.id = tv.voyage_in_id",
                    ]);

                    $where = array_merge($where, [
                        "AND DATE_FORMAT(v.date, '%Y-%m-%d') = STR_TO_DATE('" . $filter['tourVoyageInDate'] . "', '%d-%m-%Y')",
                    ]);
                }
            }
        }

        if (!array_key_exists('direction', $filter) or !in_array($filter['direction'], ['ASC', 'DESC'])) {
            $filter['direction'] = 'DESC';
        }

        switch ($filter['field']) {
            case 'id':
                $order = array_merge($order, [
                    "t.id " . $filter['direction'],
                ]);

                break;
            case 'tourDate':
                $order = array_merge($order, [
                    "t.create_date " . $filter['direction'],
                ]);

                break;
            case 'tour':
                $join = array_merge($join, [
                    "LEFT JOIN " . SiteContentModel::getFullModelTableName() . " sc_tour ON sc_tour.id = t.tour_id",
                ]);

                $order = array_merge($order, [
                    "sc_tour.pagetitle " . $filter['direction'],
                ]);

                break;
            default:
                $order = array_merge($order, [
                    "t.create_date DESC",
                ]);

                break;
        }

        $ordersCounts = $this->getList([
            'join' => $join,
            'where' => $where,
            'group' => [
                "t.id",
            ],
        ]);

        $total = (is_array($ordersCounts) ? count($ordersCounts) : 0);

        if (is_null($limit)) {
            $limit = $total;
        }

        $pages = ($limit > 0) ? ceil($total / $limit) : 0;

        $ordersList = $this->getList([
            'join' => $join,
            'where' => $where,
            'order' => $order,
            'group' => [
                "t.id",
            ],
            'limit' => $limit,
            'offset' => ($limit * ($page - 1)) < 0 ? 0 : ($limit * ($page - 1)),
        ], true);

        $result = [
            'ordersList' => $ordersList,
            'page' => $page,
            'item' => [
                'start' => (($page - 1) * $limit) + 1,
                'end' => (($page - 1) * $limit) + $limit,
            ],
            'pages' => $pages,
            'limit' => $limit,
            'total' => $total,
        ];

        return $result;
    }

    public function deleteOrder($id)
    {
        $result = $this->update([
            'status_id' => OrdersStatusesModel::STATUS_DELETED,
        ], "id = '{$id}'");

        if ($result) {
            return true;
        }

        return false;
    }

    public function deleteOrdersByUser($userId = null)
    {
        if (is_null($userId)) {
            $userId = WebUsersModel::model()->getId();
        }

        $result = $this->update([
            'status_id' => OrdersStatusesModel::STATUS_DELETED,
        ], "user_id = '{$userId}'");

        if ($result) {
            return true;
        }

        return false;
    }

    public function getOrdersByUserId($userId, $page, $limit)
    {
        $userId = intval($userId);
        $page = intval($page);
        $limit = intval($limit);

        $ordersCounts = $this->getItem([
            'select' => [
                'COUNT(*) AS count',
            ],
            'where' => [
                "t.user_id = $userId"
            ]
        ]);

        $total = intval($ordersCounts['count']);
        $pages = ceil($total / $limit);

        $ordersList = $this->getList([
            'where' => [
                "t.user_id = $userId"
            ],
            'order' => [
                't.create_date DESC',
            ],
            'limit' => $limit,
            'offset' => ($limit * ($page - 1)) < 0 ? 0 : ($limit * ($page - 1)),
        ], true);

        $result = [
            'ordersList' => $ordersList,
            'pages' => $pages
        ];

        return $result;
    }

    public function getOrdersByTourSiteContentId($siteContentId, $page, $limit)
    {
        $siteContentId = intval($siteContentId);
        $page = intval($page);
        $limit = intval($limit);

        $ordersCounts = $this->getItem([
            'select' => [
                'COUNT(*) AS count',
            ],
            'join' => [
                "JOIN evo_tours_voyages as e_tv ON t.voyage_id = e_tv.id"
            ],
            'where' => [
                "e_tv.site_content_id = $siteContentId",
                "AND `e_tv`.`deleted` = 0"
            ]
        ]);

        $total = intval($ordersCounts['count']);
        $pages = ceil($total / $limit);

        $ordersList = $this->getList([
            'join' => [
                "JOIN evo_tours_voyages as e_tv ON t.voyage_id = e_tv.id"
            ],
            'where' => [
                "e_tv.site_content_id = $siteContentId",
                "AND `e_tv`.`deleted` = 0"
            ],
            'order' => [
                't.create_date DESC',
            ],
            'limit' => $limit,
            'offset' => ($limit * ($page - 1)) < 0 ? 0 : ($limit * ($page - 1)),
        ], true);

        $result = [
            'ordersList' => $ordersList,
            'pages' => $pages
        ];

        return $result;
    }

    public function getOrderByIdAndUserId($id, $userId)
    {
        return $this->getItem([
            'where' => [
                "t.id = '" . $id . "' AND t.user_id = '" . $userId . "'"
            ],
        ], true);
    }

    public function getHotelAddressById($id)
    {
        return $this->getItem([
            'select' => [
                "t.value as address"
            ],
            'from' => "`evo_site_tmplvar_contentvalues` ",
            'where' => [
                "t.tmplvarid = 47 AND t.contentid = '" . $id . "'"
            ]
        ]);
    }

    public function getOrderUserMail($orderId)
    {
        $order = $this->getOrderById($orderId);

        if ($order) {
            return $order['user']['attributes']['email'];
        }

        return false;
    }

    public function setOrder($tourId = null, $userId = null, $price = 0, $comment = null, array $properties = [])
    {
        $orderId = null;
        $statusId = OrdersStatusesModel::STATUS_WAITING_FOR_CONFIRMATION;

        $userId = !is_null($userId) && !empty($userId) ? intval($userId) : WebUsersModel::model()->getId();

        $fields = array_filter([
            'tour_id' => !is_null($tourId) && !empty($tourId) ? intval($tourId) : null,
            'user_id' => !is_null($userId) && !empty($userId) ? intval($userId) : null,
            'price' => !is_null($price) && !empty($price) ? floatval($price) : 0,
            'comment' => !is_null($comment) && !empty($comment) ? trim($comment) : null,
            'status_id' => $statusId,
        ]);

        if ($this->insert($fields)) {
            $orderId = $this->getInsertId();

            if ($orderId) {
                if (is_array($properties) and !empty($properties)) {
                    foreach ($properties as $key => $value) {
                        OrdersPropertiesModel::model()->insert([
                            'order_id' => $orderId,
                            'key' => $key,
                            'value' => $value,
                        ]);
                    }
                }

                if ($userId) {
                    $threadId = WebUserThreadsModel::model()->addThread($orderId, "Обсуждение заказа #{$orderId}", $userId);

                    $status = OrdersStatusesModel::model()->getItem([
                        'where' => [
                            "t.id = '{$statusId}'",
                        ],
                    ]);

                    WebUserThreadMessagesModel::model()->addStatus($threadId, "Заявка создана. Статус заявки изменен на \'{$status['name']}\'");

                    if (!is_null($userId) and !is_null($orderId)) {
                        (new MailerController)->actionSendNewOrder($userId, $orderId);
                    }
                }
            }
        }

        return $orderId;
    }

    public function prepareOrderList($ordersList)
    {
        $results = array();

        if (is_array($ordersList) and !empty($ordersList)) {
            foreach ($ordersList as $index => $order) {
                $orderId = intval($order['id']);
                $tour = TourHelper::getTourFromOrder($orderId);
                $tourId = intval($tour['id']);
                $tourName = $tour['pagetitle'];
                $roomId = 0;
                $roomName = '';

                if (array_key_exists('room', $order) and !is_null($order['room']) and !empty($order['room'])) {
                    $roomId = intval($order['room']['id']);
                    $roomName = $order['room']['pagetitle'];
                } else if ((!array_key_exists('room', $order) or is_null($order['room']) or empty($order['room'])) and !is_null($order['room_id'])) {
                    $residence = ToursResidencesModel::model()->getItem([
                        'where' => [
                            "t.id = '{$order['room_id']}'"
                        ],
                    ]);

                    if ($residence) {
                        $roomId = intval($residence['id']);
                        $roomName = $residence['name'];
                    }
                }

                $results[$index] = array(
                    'id' => intval($order['id']),
                    'tour' => array(
                        'id' => intval($tourId),
                        'name' => $tourName,
                    ),
                    'status' => array(
                        'id' => intval($order['status']['id']),
                        'name' => $order['status']['name'],
                    ),
                    'room' => array(
                        'id' => intval($roomId),
                        'name' => $roomName,
                    ),
                    'object' => array(
                        'id' => intval($order['room']['object']['id']),
                        'name' => $order['room']['object']['pagetitle'],
                    ),
                    'city' => array(
                        'id' => intval($order['room']['object']['city']['id']),
                        'name' => $order['room']['object']['city']['pagetitle'],
                    ),
                    'region' => array(
                        'id' => intval($order['room']['object']['city']['region']['id']),
                        'name' => $order['room']['object']['city']['region']['pagetitle'],
                    ),
                    'country' => array(
                        'id' => intval($order['room']['object']['city']['region']['country']['id']),
                        'name' => $order['room']['object']['city']['region']['country']['pagetitle'],
                    ),
                    'persons' => array(),
                    //'price' => intval($order['price']),
                    'payment' => 0,
                    'user_id' => intval($order['user_id']),
                    'bus' => $order['bus'],
                    'comment' => $order['comment'],
                    'meal' => $order['meal'],
                    'create_date' => DatesHelper::getSpelledDate($order['create_date']),
                    'create_date_year' => FormatHelper::dateConvert($order['create_date'], 'Y-m-d H:i:s', 'Y'),
                    'create_date_time' => FormatHelper::dateConvert($order['create_date'], 'Y-m-d H:i:s', 'H:i'),
                    'update_date' => is_null($order['update_date']) ? null : DatesHelper::getSpelledDate($order['update_date']),
                    'update_date_year' => is_null($order['update_date']) ? null : FormatHelper::dateConvert($order['update_date'], 'Y-m-d H:i:s', 'Y'),
                    'update_date_time' => is_null($order['update_date']) ? null : FormatHelper::dateConvert($order['update_date'], 'Y-m-d H:i:s', 'H:i'),

                    // Базовая стоимость тура
                    'priseTourBase' => floatval($this->getOrderBasePrice($orderId)),
                    // Стоимость тура с учётом скидок и штрафов
                    'priceTour' => floatval($this->getOrderPrice($orderId)),
                    // Суммарный остаток к оплате
                    'priceBalance' => floatval($this->getOrderBalancePayments($orderId)),
                    // Сумма фактических оплат
                    'payments' => floatval($this->getOrderTotalPayments($orderId)),
                    // Сумма штрафов и скидок
                    'priceMod' => floatval($this->getOrderModPayments($orderId)),
                    // Сумма штрафов
                    'priceSanctions' => floatval($this->getOrderSanctionPayments($orderId)),
                    // Сумма скидок
                    'priceSales' => floatval($this->getOrderSales($orderId)),
                    // Сумма персональной скидки
                    'priceUserSale' => floatval($this->getOrderUserSales($orderId)),

                    // Валюта тура
                    'currency' => $order['tour']['tv']['priceLabel'],
                );

                if (array_key_exists('arrive', $order)) {
                    $results[$index]['arrives'] = array(
                        'id' => intval($order['arrive']['id']),
                        'places' => intval($order['arrive']['places']),
                        'price' => intval($order['arrive']['price']),
                        'reserved' => intval($order['arrive']['reserved']),
                        'date_from' => DatesHelper::getSpelledDate($order['arrive']['date_from']),
                        'date_to' => DatesHelper::getSpelledDate($order['arrive']['date_to']),
                        'date_from_year' => FormatHelper::dateConvert($order['arrive']['date_from'], 'Y-m-d H:i:s', 'Y'),
                        'date_to_year' => FormatHelper::dateConvert($order['arrive']['date_to'], 'Y-m-d H:i:s', 'Y'),
                    );
                }

                if (array_key_exists('voyage', $order)) {
                    $results[$index]['arrives'] = array(
                        'id' => intval($order['voyage']['id']),
                        'places' => 0,
                        'price' => 0,
                        'reserved' => 0,
                        'date_from' => DatesHelper::getSpelledDate($order['voyage']['voyage_out']['date']),
                        'date_to' => DatesHelper::getSpelledDate($order['voyage']['voyage_in']['date']),
                        'date_from_year' => FormatHelper::dateConvert($order['voyage']['voyage_out']['date'], 'Y-m-d H:i:s', 'Y'),
                        'date_to_year' => FormatHelper::dateConvert($order['voyage']['voyage_in']['date'], 'Y-m-d H:i:s', 'Y'),
                    );
                }

                if (is_array($order['persons']) and !empty($order['persons'])) {
                    foreach ($order['persons'] as $personIndex => $person) {
                        $results[$index]['persons'][$personIndex]['id'] = intval($person['id']);
                        $results[$index]['persons'][$personIndex]['surname'] = $person['surname'];
                        $results[$index]['persons'][$personIndex]['name'] = $person['name'];
                        $results[$index]['persons'][$personIndex]['middlename'] = $person['middlename'];
                        $results[$index]['persons'][$personIndex]['email'] = $person['email'];
                        $results[$index]['persons'][$personIndex]['document'] = $person['document'];
                        $results[$index]['persons'][$personIndex]['date'] = $person['date'];
                        $results[$index]['persons'][$personIndex]['phone'] = $person['phone'];
                        $results[$index]['persons'][$personIndex]['comment'] = $person['comment'];
                    }
                }

                if (is_array($order['payments']) and !empty($order['payments'])) {
                    foreach ($order['payments'] as $paymentIndex => $payment) {
                        $results[$index]['payment'] += intval($payment['value']);
                    }
                }
            }
        }

        return $results;
    }

    public function updateTourOrderPrice($orderId, $price)
    {
        return $this->update([
            'price' => $price,
        ], "id = '{$orderId}'");
    }
}
