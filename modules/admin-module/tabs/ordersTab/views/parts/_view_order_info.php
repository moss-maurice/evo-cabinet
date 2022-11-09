<?php

use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\ToursResidencesModel;
use mmaurice\cabinet\core\helpers\FormatHelper;

?>

<div id="order-item" rel-item-id="<?= $itemId; ?>" rel-tab="<?= $tabName; ?>" rel-method="update">
    <div class="row align-items-center">
        <div class="col">
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
        <hr>

        <?php if (is_array($order['over_price']) and !empty($order['over_price'])) : ?>
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
    <div class="col-12 col-lg-6">
        <div class="my-2 d-flex align-items-center">
            <div class="userInfoIcon">
                <?php
                if (intval($order['user']['attributes']['role']) == 5)
                    echo '<i class="fas fa-user"></i>';
                else if (intval($order['user']['attributes']['role']) == 6)
                    echo '<i class="fas fa-user-tie"></i>';
                ?>
            </div>
            <div class="">
                <?= $order['user']['attributes']['fullname']; ?>
                <small>(<?= $order['user']['id']; ?>)</small>
                <input type="hidden" id="user-id" value="<?= $order['user']['id']; ?>" />
            </div>
        </div>
        <?php if ($order['user']['settings']['type'] == 'agency') : ?>
            <div class="my-2 d-flex align-items-center">
                <small>Агентство</small> <?= $order['user']['settings']['agency']; ?>
            </div>
        <?php endif; ?>
        <?php if ($order['user']['attributes']['email']) : ?>
            <div class="my-2 d-flex align-items-center">
                <div class="userInfoIcon"><i class="fas fa-envelope"></i></div>
                <div class="">
                    <a href="mailto:<?= $order['user']['attributes']['email']; ?>">
                        <?= $order['user']['attributes']['email']; ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($order['user']['attributes']['phone']) : ?>
            <div class="my-2 d-flex align-items-center">
                <div class="userInfoIcon"><i class="fas fa-phone"></i></div>
                <div class="">
                    <?= FormatHelper::phoneFormat($order['user']['attributes']['phone']); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($order['user']['attributes']['mobilephone']) : ?>
            <div class="my-2 d-flex align-items-center">
                <div class="userInfoIcon"><i class="fas fa-mobile-alt"></i></div>
                <div class="">
                    <?= FormatHelper::phoneFormat($order['user']['attributes']['mobilephone']); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($order['user']['attributes']['fax']) : ?>
            <div class="my-2 d-flex align-items-center">
                <div class="userInfoIcon"><i class="fas fa-fax"></i></div>
                <div class="">
                    <?= FormatHelper::phoneFormat($order['user']['attributes']['fax']); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($order['user']['attributes']['city']) : ?>
            <div class="my-2 d-flex align-items-center">
                <div class="userInfoIcon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="">
                    <?= $order['user']['attributes']['city']; ?>,
                    <?= $order['user']['attributes']['street']; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
