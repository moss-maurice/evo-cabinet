<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\helpers\DatesHelper; ?>
<?php use mmaurice\cabinet\models\OrdersModel; ?>
<?php use mmaurice\cabinet\widgets\PaginatorWidget; ?>
<?php use mmaurice\cabinet\widgets\PageTitleWidget; ?>

<?= PageTitleWidget::init([
    'title' => 'Все заявки',
])->run(); ?>

<?php if (is_array($pagination['ordersList']) and !empty($pagination['ordersList'])) : ?>
<table class="table table-sm table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col" class="px-2">#</th>
            <th scope="col" class="px-2">Заявка</th>
            <th scope="col" class="px-2">Тур</th>
            <th scope="col" class="px-2">Дата создания</th>
            <th scope="col" class="px-2">Статус</th>
            <th scope="col" class="px-2">Сумма</th>
            <th scope="col" class="px-2">Оплчено</th>
            <th scope="col" class="px-2"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pagination['ordersList'] as $index => $order) : ?>
        <tr>
            <td class="p-2" scope="row"><?= ($index + 1) ?>.</td>
            <td class="p-2">№ <?= $order['id']; ?></td>
            <td class="p-2">
                <strong><?= (isset($order['tour']) ? $order['tour']['pagetitle'] : '&mdash;'); ?></strong>
            </td>
            <td class="p-2">
                <?= DatesHelper::getSpelledDate($order['create_date']); ?>
                <?php if (!is_null($order['update_date'])) : ?>
                <div>Обновлено: <?= DatesHelper::getSpelledDate($order['update_date']); ?></div>
                <?php endif; ?>
            </td>
            <td class="p-2"><?= $order['status']['name']; ?></td>
            <td class="p-2 text-right"><?= OrdersModel::model()->getOrderPrice($order['id']) ?> ₽</td>
            <td class="p-2 text-right">
                <?= (intval(OrdersModel::model()->getOrderPayments($order['id'], 0, '', '')) > 0 ? OrdersModel::model()->getOrderPayments($order['id']) . ' ₽' : 'Нет оплат'); ?>
            </td>
            <td class="py-2 pl-2 text-right">
                <a href="<?= App::init()->makeUrl('/{lk}/order', [
                    'orderId' => $order['id'],
                ]) ?>" class="bs btn btn-primary btn-sm">Подробнее</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= PaginatorWidget::init([
    'container' => '<div class="text-center pt-3 mt-5 border-top"><div class="btn-group">[content]</div></div>',
    'firstButton' => '<a class="bs btn btn-sm btn-outline-dark" href="' . App::init()->makeUrl('/{lk}/orders') . '" rel-page="1">Начало</a>',
    'lastButton' => '<a class="bs btn btn-sm btn-outline-dark" href="' . App::init()->makeUrl('/{lk}/orders', [
            'page' => '[pages]',
        ]) . '" rel-page="[pages]">Конец</a>',
    'prevButton' => '<a class="bs btn btn-sm btn-outline-dark" href="' . App::init()->makeUrl('/{lk}/orders', [
            'page' => '[prevPage]',
        ]) . '" rel-page="[prevPage]">Предыдущая</a>',
    'nextButton' => '<a class="bs btn btn-sm btn-outline-dark" href="' . App::init()->makeUrl('/{lk}/orders', [
            'page' => '[nextPage]',
        ]) . '" rel-page="[nextPage]">Следующая</a>',
    'button' => '<a class="bs btn btn-sm btn-outline-dark" href="' . App::init()->makeUrl('/{lk}/orders', [
            'page' => '[page]',
        ]) . '" rel-page="[page]">[page]</a>',
    'activeButton' => '<span class="bs btn btn-sm btn-dark text-white active" rel-page="[currentPage]"><strong>[currentPage]</strong></span>',
])->run($pagination['page'], $pagination['pages']); ?>
<?php else: ?>
<div class="alert alert-danger" role="alert">
    У вас пока нет заявок
</div>
<?php endif; ?>