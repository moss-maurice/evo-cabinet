<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;

class TransactionsTypesModel extends Model
{
    const TRANSACTION_DISCOUNT = 'discount';
    const TRANSACTION_MARGIN = 'margin';
    const TRANSACTION_PAYMENT = 'payment';
    const TRANSACTION_TEMP = 'temp';

    const TRANSACTION_DISCOUNT_ID = 1;
    const TRANSACTION_MARGIN_ID = 2;
    const TRANSACTION_PAYMENT_ID = 3;
    const TRANSACTION_TEMP_ID = 4;

    /**
     * Table name without prefix `evo_`
     *
     * @var string $tableName
     */
    public $tableName = 'transactions_types';
}