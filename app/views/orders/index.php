<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\helpers\DatesHelper; ?>
<?php use mmaurice\cabinet\models\OrdersModel; ?>
<?php use mmaurice\cabinet\widgets\PaginatorWidget; ?>

<div id="orderList" class="orders u-content">
<?php if (is_array($pagination['ordersList']) and !empty($pagination['ordersList'])) : ?>
    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">
        <table data-toggle="table" class="table table-striped table-bordered dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
                <tr role="row">
                    <th class="sorting_disabled" rowspan="1" colspan="1">Создание</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1">Заказ</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1">Тур</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1">Сумма</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1">Оплачено</th>
                    <th class="sorting_disabled" rowspan="1" colspan="1"></th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($pagination['ordersList'] as $index => $order) : ?>
                <tr role="row" class="<?= (($index % 2 == 0) ? 'even' : 'odd'); ?>">
                    <td class="text-nowrap text-right"><?= DatesHelper::getSpelledDate($order['create_date']); ?>
        <?php if (!is_null($order['update_date'])) : ?>
                        <div>Обновлено: <?= DatesHelper::getSpelledDate($order['update_date']); ?></div>
        <?php endif; ?>
                    </td>
                    <td>№ <?= $order['id']; ?><br><small><?= $order['status']['name']; ?></small></td>
                    <td>
                        <div><b><?= (isset($order['tour']) ? $order['tour']['pagetitle'] : '&mdash;'); ?></b></div>
                    </td>
                    <td class="text-right"><?= OrdersModel::model()->getOrderPrice($order['id']) ?> руб.</td>
                    <td class="text-right">
                        <div class="text-nowrap"><?= (intval(OrdersModel::model()->getOrderPayments($order['id'], 0, '', '')) > 0 ? OrdersModel::model()->getOrderPayments($order['id']) . ' руб.' : 'Нет оплат'); ?></div>
                    </td>
                    <td class="text-center">
                        <a href="/lk/order?orderId=<?= $order['id']; ?>" class="js-open-order btn">Подробнее</a>
                    </td>
                </tr>
    <?php endforeach; ?>
            </tbody>
        </table>
        <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Записи с <?= ($pagination['item']['start']); ?> по <?= ($pagination['item']['end']); ?> из <?= ($pagination['total']); ?> записей</div>
        <?= PaginatorWidget::init([
            'container' => '<div class="pagination d-flex justify-content-center align-items-baseline">[content]</div>',
            'firstButton' => '<a href="' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '" rel-page="1">Начало</a>',
            'lastButton' => '<a href="' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?page=[pages]" rel-page="[pages]">Конец</a>',
            'prevButton' => '<a href="' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?page=[prevPage]" rel-page="[prevPage]">Предыдущая</a>',
            'nextButton' => '<a href="' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?page=[nextPage]" rel-page="[nextPage]">Следующая</a>',
            'button' => '<a href="' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?page=[page]" rel-page="[page]">[page]</a>',
            'activeButton' => '<a class="active" rel-page="[currentPage]"><strong>[currentPage]</strong></a>',
        ])->run($pagination['page'], $pagination['pages']); ?>
    </div>
<?php else : ?>
    <p><strong>У вас пока нет заявок</strong></p>
<?php endif;?>
</div>