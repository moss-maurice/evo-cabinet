<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;

class OrdersStatusesModel extends Model
{
    const STATUS_ALL = 0;

    const STATUS_NEW = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_PAYED = 3;
    const STATUS_DONE = 4;
    const STATUS_ARCHIVE = 5;
    const STATUS_DELETED = 6;
    const STATUS_CANCELED = 7;

    public static $statuses = [
        1 => 'Новая',
        2 => 'Подтверждена',
        3 => 'Оплачена',
        4 => 'Завершена',
        5 => 'В архиве',
        6 => 'Удалена',
        7 => 'Отменена',
    ];

    public $tableName = 'orders_statuses';
}
