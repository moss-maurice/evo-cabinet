<?php

use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\core\helpers\FormatHelper;

?>

<h3>Оплаты</h3>
<h4 class="text-left alert alert-info">
    <small>Сумма: <?= OrdersModel::model()->getOrderBasePrice($order['id']); ?> <?= $tour['tv']['priceLabel']; ?></small>
    <small> | </small>
<?php /*
    <!-- <small>Стоимость тура: <?= OrdersModel::model()->getOrderPrice($order['id']); ?> <?= $tour['tv']['priceLabel']; ?></small> -->
    <!-- <small> | </small> -->
    <!-- <small>Платежи: <?= OrdersModel::model()->getOrderTotalPayments($order['id']) ?> <?= $tour['tv']['priceLabel']; ?></small> -->
    <!-- <small> | </small> -->
    <!-- <small>Штрафы/Скидки: <span id="payments-sum-all"><?= OrdersModel::model()->getOrderModPayments($order['id']); ?></span> <?= $tour['tv']['priceLabel']; ?></small> -->
    <!-- <small> | </small> -->
*/ ?>
    <small>Остаток: <span id="payments-price-balance"><?= OrdersModel::model()->getOrderBalancePayments($order['id']); ?></span> <?= $tour['tv']['priceLabel']; ?></small>
</h4>

<table class="table data mb-4" cellpadding="1" cellspacing="1" id="payments-data-table">
    <thead>
        <tr>
            <td class="tableHeader"># Оплаты</td>
            <td class="tableHeader">Плательщик</td>
            <td class="tableHeader">Тип</td>
            <td class="tableHeader text-right">Размер</td>
            <td class="tableHeader">Комментарий</td>
            <td class="tableHeader">Дата</td>
            <td class="tableHeader"></td>
        </tr>
    </thead>
    <?php if (is_array($order['payments']) and !empty($order['payments'])) : ?>
        <tbody>
            <?php foreach ($order['payments'] as $payment) : ?>
                <?php if (!intval($payment['deleted'])) : ?>
                    <tr>
                        <td class="tableItem"><?= $payment['id']; ?></td>
                        <td class="tableItem">
                            <strong><?= trim($payment['payer'] ? $payment['payer'] : $payment['editor']); ?></strong>
                        </td>
                        <td class="tableItem"><?= $payment['transaction_type']['title']; ?></td>
                        <td class="tableItem text-right">
                            <?= number_format($payment['transaction_value'], 2, '.', ''); ?> <?= $tour['tv']['priceLabel']; ?>
                        </td>
                        <td class="tableItem"><?= (!empty($payment['comment']) ? $payment['comment'] : '&mdash;'); ?></td>
                        <td class="tableItem"><?= FormatHelper::dateConvert($payment['create_date'], 'Y-m-d H:i:s', 'd.m.Y H:i:s'); ?></td>
                        <td class="tableItem text-right">
                            <?php if (intval($payment['raw_transaction_type']['id']) === 1) : ?>
                                <div class="btn-group">
                                    <span id="Button5" class="btn btn-danger lk-module-transaction-delete-button" rel-tab="<?= $tabName; ?>" rel-method="deleteOrderPayment" rel-item-id="<?= $payment['id']; ?>" rel-order-id="<?= $order['id']; ?>">Удалить</span>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    <?php endif; ?>
    <tfoot>
        <tr>
            <td class="tableHeader text-right">Стоимость:</td>
            <td class="tableHeader"><?= OrdersModel::model()->getOrderBalancePayments($order['id']); ?> <?= $tour['tv']['priceLabel']; ?></td>
            <td class="tableHeader text-right">Итого:</td>
            <td class="tableHeader"><?= OrdersModel::model()->getOrderTotalPayments($order['id']); ?> <?= $tour['tv']['priceLabel']; ?></td>
            <td class="tableHeader text-right">Остаток: </td>
            <td class="tableHeader"><?= OrdersModel::model()->getOrderBalancePayments($order['id']); ?> <?= $tour['tv']['priceLabel']; ?></td>
            <td class="tableHeader text-right"></td>
        </tr>
    </tfoot>
    <tfoot>
        <tr>
            <td class="tableItem text-right" colspan="3">
                <strong><small>* Для ввода скидки или снижения стоимости тура поставьте знак минус в поле цены</small></strong>
            </td>
            <td class="tableItem text-right">
                <input id="transaction-amount" type="number" style="width: 100px; text-align: right;" />
            </td>
            <td class="tableItem text-right">
                <input id="transaction-description" type="text" placeholder="Укажите примечание" />
            </td>
            <td class="tableItem"></td>
            <td class="tableItem text-right">
                <div class="btn-group">
                    <span id="Button5" class="btn btn-success lk-module-transaction-add-button" rel-tab="<?= $tabName; ?>" rel-order-id="<?= $order['id']; ?>" rel-method="addOrderPayment">Добавить</span>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
