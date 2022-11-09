<?php

use mmaurice\cabinet\helpers\TourHelper;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\OrdersStatusesModel;
use mmaurice\cabinet\core\helpers\FormatHelper;

?>

        <tbody>
    <?php foreach ($ordersList as $orderItem) : ?>
        <?php if (is_array($orderItem) and !empty($orderItem)) : ?>
            <?php $orderId = intval($orderItem['id']); ?>
            <?php $tour = TourHelper::getTourFromOrder($orderId); ?>
            <?php $tourId = intval($tour['id']); ?>
            <?php $tourName = $tour['pagetitle']; ?>
            <?php $payments = 0; ?>
            <?php if (!is_null($orderItem['payments'])) : ?>
                <?php foreach ($orderItem['payments'] as $payment) : ?>
                    <?php $payments += $payment['value']; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr data-id="<?= $orderItem['id']; ?>">
                <td class="tableItem"><?= $orderItem['id']; ?></td>
                <td class="tableItem">
                    <?= FormatHelper::dateAutoFormat($orderItem['create_date'], 'Y-m-d H:i:s'); ?>
                </td>
                <td class="tableItem">
            <?php if (!is_null($tourName)) : ?>
                    <strong><?= $tourName; ?></strong>
            <?php else: ?>
                    &mdash;
            <?php endif; ?>
                </td>
                <td class="tableItem"><?= floatval(OrdersModel::model()->getOrderTotalPayments($orderItem['id'])); ?> / <?= floatval(OrdersModel::model()->getOrderBalancePayments($orderItem['id'])); ?></td>
                <td class="tableItem"><?= $orderItem['status']['name']; ?></td>
                <td class="tableItem">
                    <span rel-item-id="<?= $orderItem['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="view" class="btn btn-primary orderLink lk-module-button" title="Подробности заявки">
                        <i class="fa fa-eye"></i>
                    </span>
                </td>
            <?php if (!in_array(intval($orderItem['status']['id']), [OrdersStatusesModel::STATUS_ARCHIVE, OrdersStatusesModel::STATUS_DELETED])) : ?>
                <td class="tableItem">
                    <span rel-item-id="<?= $orderItem['id']; ?>" rel-tab="<?= $tabName; ?>" rel-tab-method="remove" class="btn btn-danger removeOrder lk-module-remove-order-button" title="Удалить заявку">
                        <i class="fa fa-trash"></i>
                    </span>
                </td>
                <td class="tableItem">
                    <span rel-item-id="<?= $orderItem['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="mails" class="btn orderLink lk-module-button" title="Тестирование отправки писем">
                        <i class="fa fa-envelope"></i>
                    </span>
                </td>
            <?php endif; ?>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
        </tbody>
