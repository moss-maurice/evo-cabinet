<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;

class OrdersStatusesModel extends Model
{
    const STATUS_ALL = 0;

    const STATUS_WAITING_FOR_CONFIRMATION = 1;
    const STATUS_AVAILABLE_FOR_PAYMENT = 2;
    const STATUS_CANCELED = 3;
    const STATUS_NOT_CONFIRMED = 4;
    const STATUS_PAID = 5;
    const STATUS_CONFIRMED = 6;
    const STATUS_ARCHIVE = 7;
    const STATUS_DELETED = 8;

    public static $statuses = [
        1 => 'Ожидает подтверждение',
        2 => 'Доступна к оплате',
        3 => 'Отменена',
        4 => 'Не подтверждена',
        5 => 'Оплачена',
        6 => 'Подтверждена',
        7 => 'Архивная',
        8 => 'Удалена',
    ];

    public $tableName = 'orders_statuses';
}
