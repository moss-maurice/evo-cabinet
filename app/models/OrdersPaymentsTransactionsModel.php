<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\OrdersPaymentsTransactionsReasonsModel;
use mmaurice\cabinet\models\OrdersStatusesModel;
use mmaurice\cabinet\models\TransactionsTypesModel;
use mmaurice\cabinet\models\WebUsersModel;

class OrdersPaymentsTransactionsModel extends Model
{
    const UNDELETED = 0;
    const DELETED = 1;

    /**
     * Table name without prefix `evo_`
     *
     * @var string $tableName
     */
    public $tableName = 'orders_payments_transactions';
    public $relations = [
        'user' => ['user_id', [WebUsersModel::class, 'id'], self::REL_ONE],
        'transaction_type' => ['transaction_type', [TransactionsTypesModel::class, 'id'], self::REL_ONE],
    ];

    public function addUserPaymentTransaction(array $data)
    {
        return $this->insert($data);
    }

    public static function applyOrderPersonalSale($orderId)
    {
        $statuses = [
            OrdersStatusesModel::STATUS_CONFIRMED,
        ];

        // Верифицируем заявку, что она имеет нужный нам статус
        $order = OrdersModel::model()->getItem([
            'select' => [
                "t.*",
            ],
            'where' => [
                "t.id = '{$orderId}'",
                "AND t.status_id IN ('" . implode("','", $statuses) . "')",
            ],
        ]);

        if ($order) {
            if (is_null($userId)) {
                $userId = intval($order['user_id']);
            }

            // Подбираем данные по юзеру
            $user = WebUsersModel::model()->getItem([
                'select' => [
                    "t.*",
                ],
                'where' => [
                    "t.id = '{$userId}'",
                ],
            ], true);

            if ($user) {
                // Получаем скидку юзера
                $saleValue = array_shift(array_map(function($value) {
                    return $value['setting_value'];
                }, array_filter($user['settings'], function ($value) {
                    if ($value['setting_name'] === 'sale') {
                        return true;
                    }

                    return false;
                })));

                // Займёмся ценой и скидкой
                $price = floatval($order['price']);
                $sale = (0 - self::getNaturalSale($saleValue, $price));
                $saleDesc = self::isAbsoluteSale($saleValue) ? "{$saleValue} руб." : self::extractRelativeSaleValue($saleValue) . "%";

                if ($sale < 0) {
                    // Находим транзакцию по заявкам
                    $personalSaleTransaction = self::model()->getItem([
                        'select' => [
                            "t.*",
                        ],
                        'where' => [
                            "t.deleted = '0'",
                            "AND t.order_id = '{$orderId}'",
                            "AND t.transaction_type = '" . TransactionsTypesModel::TRANSACTION_DISCOUNT . "'",
                        ],
                    ]);

                    if ($personalSaleTransaction) {
                        if ($personalSaleTransaction['transaction_value'] == $sale) {
                            return true;
                        }

                        self::model()->deleteAdminPaymentTransaction($personalSaleTransaction['id']);
                    }

                    self::model()->insert([
                        'transaction_value' => $sale,
                        'transaction_type' => TransactionsTypesModel::TRANSACTION_DISCOUNT_ID,
                        'order_id' => $order['id'],
                        'user_id' => $userId,
                        'editor' => 'system',
                        'comment' => "Автоматическое применение пользовательской скидки ({$saleDesc})",
                    ]);
                }
            }
        }

        return false;
    }

    public static function getNaturalSale($sale, $price)
    {
        $price = floatval($price);

        if (self::isAbsoluteSale($sale)) {
            return floatval($sale);
        }

        return (($price / 100) * self::extractRelativeSaleValue($sale));
    }

    public static function isAbsoluteSale($sale)
    {
        if (preg_match('/([^\s]+)\s*([\%])/im', $sale, $matches)) {
            return false;
        }

        return true;
    }

    public static function extractRelativeSaleValue($sale)
    {
        if (!self::isAbsoluteSale($sale)) {
            if (preg_match('/([^\s]+)\s*([\%])/im', $sale, $matches)) {
                return floatval($matches[1]);
            }
        }

        return null;
    }

    public static function applyUserOrdersPersonalSale($userId)
    {
        // В принципе собираем все заявки юзера и не паримся, потому что валидировать их будем в другом методе
        $orders = OrdersModel::model()->getList([
            'select' => [
                "t.*",
            ],
            'where' => [
                "t.user_id = '{$userId}'",
            ],
        ]);

        if ($orders) {
            foreach ($orders as $order) {
                self::applyOrderPersonalSale($order['id']);
            }
        }

        return true;
    }

    public function addTransaction($orderId, $userId, $transactionType, $transactionValue)
    {
        $paymentId = explode('.', microtime(true));
        $paymentId = $paymentId[1] . rand(0, 9) . $paymentId[0];

        return $this->insert([
            'payment_id' => $paymentId,
            'order_id' => $orderId,
            'user_id' => $userId,
            'transaction_type' => $transactionType,
            'transaction_value' => $transactionValue,
        ]);
    }

    public function addAdminPaymentTransaction(array $data)
    {
        $transactionValue = $data['transactionValue'];
        $transactionType = $data['transactionType'];
        $orderId = $data['orderId'];
        $comment = $data['comment'];

        $sql = "INSERT INTO `evo_web_user_payment_transactions`
            (`transaction_value`, `transaction_type`, `order_id`, `comment`)
            VALUES ('{$transactionValue}', '{$transactionType}', '{$orderId}', '{$comment}');";

        if ($this->query($sql))
            $result = $this->getInsertId();

        return $result;
    }

    public function updateAdminPaymentTransaction($orderId, $paymentId, $comment, $transactionValue, $transactionType)
    {
        return $this->update([
            'transaction_value' => $transactionValue,
            'transaction_type' => $transactionType,
            'order_id' => $orderId,
            'comment' => $comment,
        ], "`id` = '{$paymentId}'");
    }

    public function deleteAdminPaymentTransaction($id)
    {
        $id = intval($id);

        $sql = "UPDATE `evo_web_user_payment_transactions` SET `deleted` = 1
            WHERE `id` = '{$id}';";

        $result = $this->query($sql);

        return $result;
    }

    public function getPaymentsByOrderId($id)
    {
        $id = intval($id);

        $payments = $this->getList([
            'select' => [
                "t.id",
                "t.transaction_value",
                "t.transaction_type AS raw_transaction_type",
                "tt.title AS transaction_type",
                "wu.username AS payer",
                "t.editor AS editor",
                "t.comment",
                "t.create_date AS date",
                "t.deleted",
            ],
            'join' => [
                "LEFT JOIN " . WebUsersModel::getFullModelTableName() . " as wu ON wu.id = t.user_id",
                "LEFT JOIN " . OrdersModel::getFullModelTableName() . " as o ON o.id = t.order_id",
                "LEFT JOIN " . TransactionsTypesModel::getFullModelTableName() . " as tt ON tt.id = t.transaction_type",
            ],
            'where' => [
                "t.order_id = '{$id}'",
                "AND t.deleted = '0'",
            ],
            'order' => [
                "t.id DESC",
            ]
        ]);

        return $payments;
    }
}
