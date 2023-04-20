<?php

use mmaurice\cabinet\models\OrdersStatusesModel;
use mmaurice\cabinet\widgets\SortChevronWidget;

?>

        <thead>
            <tr class="paginator">
                <td class="tableHeader" colspan="<?= (!in_array(intval($orderItem['status']['id']), [OrdersStatusesModel::STATUS_ARCHIVE, OrdersStatusesModel::STATUS_DELETED]) ? 8 : 6); ?>">
                    <?php include realpath(dirname(__FILE__) . '/_list_pagination.php'); ?>
                </td>
            </tr>
            <tr>
                <td class="tableHeader">#
    <?= SortChevronWidget::init([
        'page' => $page,
        'tab' => $tabName,
        'method' => $tabMethod,
        'field' => 'id',
        'direction' => (isset($directions['id']) ? $directions['id'] : 'ASC'),
        'active' => (isset($directions['id']) ? true : false),
    ])->draw('id'); ?>
                </td>
                <td class="tableHeader">Дата
    <?= SortChevronWidget::init([
        'page' => $page,
        'tab' => $tabName,
        'method' => $tabMethod,
        'field' => 'tourDate',
        'direction' => (isset($directions['tourDate']) ? $directions['tourDate'] : 'ASC'),
        'active' => (isset($directions['tourDate']) ? true : false),
    ])->draw('tourDate'); ?>
                </td>
                <td class="tableHeader">Тур
    <?= SortChevronWidget::init([
        'page' => $page,
        'tab' => $tabName,
        'method' => $tabMethod,
        'field' => 'tour',
        'direction' => (isset($directions['tour']) ? $directions['tour'] : 'ASC'),
        'active' => (isset($directions['tour']) ? true : false),
    ])->draw('tour'); ?>
                </td>
                <td class="tableHeader">Оплата</td>
                <td class="tableHeader">Статус</td>
                <td class="tableHeader"></td>
            </tr>
        </thead>
