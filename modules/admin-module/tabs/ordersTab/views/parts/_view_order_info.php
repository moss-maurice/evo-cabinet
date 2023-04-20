<?php

use mmaurice\cabinet\core\helpers\FormatHelper;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\ToursResidencesModel;

?>

<div id="order-item" rel-item-id="<?= $itemId; ?>" rel-tab="<?= $tabName; ?>" rel-method="update">
    <div class="row align-items-center">
        <div class="col-6">
            <h1>Заявка # <?= $itemId; ?></h1>
        </div>
        <div class="col ml-auto">
            Статус
            <select id="status-id" name="status_id" size="1" class="form-control" onchange="documentDirty=true;">
                <?php if (is_array($statuses) and !empty($statuses)) : ?>
                    <?php foreach ($statuses as $status) : ?>
                        <option value="<?= $status['id']; ?>" <?= (intval($status['id']) === intval($order['status']['id'])) ? ' selected="selected"' : ''; ?>><?= $status['name']; ?></option>
                    <?php endforeach ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 col-lg-6">
        <table>
            <tr>
                <td>Дата заявки</td>
                <td><?= FormatHelper::dateConvert($order['create_date'], 'Y-m-d H:i:s', 'd.m.Y'); ?> <small><?= FormatHelper::dateConvert($order['create_date'], 'Y-m-d H:i:s', 'H:i'); ?></small></td>
            </tr>
            <tr>
                <td>Тур</td>
                <td>
                    <?php if (!is_null($tour)) : ?>
                        <?= $tour['pagetitle']; ?>
                        <small>(<?= $tour['id']; ?>)</small>
                        <input type="hidden" id="tour_id" name="tour_id" value="<?= $tour['id']; ?>" tvtype="text" onchange="documentDirty=true;" style="width:100%">
                    <?php else : ?>
                        &mdash;
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Изменить базовую стоимость тура</td>
                <td>
                    <input type="number" style="width:100px;" class="update-tour-price-cost" value="<?= intval(OrdersModel::model()->getOrderBasePrice($order['id'])); ?>"> <?= $tour['tv']['priceLabel']; ?>
                    <input type="button" class="btn btn-secondary-save-tour-price-cost d-none" value="Сохранить">
                </td>
            </tr>

            <?php if (!empty($order['comment'])) : ?>
                <tr>
                    <td>Комментарий</td>
                    <td>
                        <?= nl2br($order['comment']) ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>

        <?php if (is_array($order['over_price']) and !empty($order['over_price'])) : ?>
            <hr />
            <div class="row align-items-center">
                <div class="col">
                    <h1>Свойства заявки</h1>
                </div>
            </div>

            <table class="table data md-4" cellpadding="1" cellspacing="1">
                <thead>
                    <tr>
                        <td class="tableHeader" width="34">#</td>
                        <td class="tableHeader" width="34">Title</td>
                        <td class="tableHeader" width="34">Value</td>
                        <td class="tableHeader" width="34">Required</td>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($order['over_price'] as $item) : ?>
                        <?php if (!empty($item) and !is_null($item)) : ?>
                            <tr>
                                <td class="tableItem" width="34"><?= $item['id']; ?></td>
                                <td class="tableItem" width="34"><?= $item['title']; ?></td>
                                </td>
                                <td class="tableItem" width="34"><?= $item['value']; ?></td>
                                </td>
                                <td class="tableItem" width="34"><?= $item['required'] ? 'required' : 'not required' ?></td>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
