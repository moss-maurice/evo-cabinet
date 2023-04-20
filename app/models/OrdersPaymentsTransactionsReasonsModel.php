<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\OrdersStatusesModel;
use mmaurice\cabinet\models\TransactionsTypesModel;
use mmaurice\cabinet\models\WebUsersModel;

class OrdersPaymentsTransactionsReasonsModel extends Model
{
    const STATUS_ALL = 0;

    const REASON_DISCOUNT = 1;
    const REASON_MARGIN = 2;
    const REASON_PAYMENT = 3;

    public static $statuses = [
        1 => 'Скидка',
        2 => 'Наценка',
        3 => 'Оплата',
    ];

    public $tableName = 'orders_payments_transactions_reasons';
}
