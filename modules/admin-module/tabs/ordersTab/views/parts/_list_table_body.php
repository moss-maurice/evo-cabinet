<?php

use mmaurice\cabinet\core\helpers\FormatHelper;
use mmaurice\cabinet\helpers\TourHelper;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\OrdersStatusesModel;

?>

        <tbody>
    <?php foreach ($ordersList as $orderItem) : ?>
        <?php if (is_array($orderItem) and !empty($orderItem)) : ?>
            <?php $orderId = intval($orderItem['id']); ?>
            <?php $tour = TourHelper::getTourFromOrder($orderId); ?>
            <tr data-id="<?= $orderItem['id']; ?>">
                <td class="tableItem"><?= $orderItem['id']; ?>.</td>
                <td class="tableItem">
                    <?= FormatHelper::dateAutoFormat($orderItem['create_date'], 'Y-m-d H:i:s'); ?>
                </td>
                <td class="tableItem">
            <?php if (!is_null($tour['pagetitle'])) : ?>
                    <strong><?= $tour['pagetitle']; ?></strong>
            <?php else: ?>
                    &mdash;
            <?php endif; ?>
                </td>
                <td class="tableItem"><?= floatval(OrdersModel::model()->getOrderTotalPayments($orderItem['id'])); ?> ₽ / <?= floatval(OrdersModel::model()->getOrderPrice($orderItem['id'])); ?> ₽</td>
                <td class="tableItem"><?= $orderItem['status']['name']; ?></td>
                <td class="tableItem" width="1%">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <span rel-item-id="<?= $orderItem['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="view" class="btn btn-success orderLink lk-module-button" title="Подробности заявки">
                            <i class="fa fa-pen"></i>
                        </span>
                    <?php if (!in_array(intval($orderItem['status']['id']), [OrdersStatusesModel::STATUS_ARCHIVE, OrdersStatusesModel::STATUS_DELETED])) : ?>
                            <span rel-item-id="<?= $orderItem['id']; ?>" rel-tab="<?= $tabName; ?>" rel-tab-method="remove" class="btn btn-danger removeOrder lk-module-remove-order-button" title="Удалить заявку">
                                <i class="fa fa-trash"></i>
                            </span>
                            <span rel-item-id="<?= $orderItem['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="mails" class="btn btn-primary orderLink lk-module-button" title="Тестирование отправки писем">
                                <i class="fa fa-envelope"></i>
                            </span>
                    <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
        </tbody>
