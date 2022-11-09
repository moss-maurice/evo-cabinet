<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;

class TransactionsTypesModel extends Model
{
    const EDITED_BY_ADMIN = 'editedByAdmin';
    const INTERNET_PAYMENT = 'internetPayment';
    const TRANSACTION_TO_CARD = 'transactionToCard';
    const CASH_TO_BANK_BY_PDF = 'cashToBankByPDF';
    const TMP_TRANSACTION = 'tmpTransaction';
    const PERSONAL_SALE = 'personalSale';

    const EDITED_BY_ADMIN_ID = 1;
    const INTERNET_PAYMENT_ID = 2;
    const TRANSACTION_TO_CARD_ID = 3;
    const CASH_TO_BANK_BY_PDF_ID = 4;
    const TMP_TRANSACTION_ID = 5;
    const PERSONAL_SALE_ID = 6;

    /**
     * Table name without prefix `evo_`
     *
     * @var string $tableName
     */
    public $tableName = 'transactions_types';
}