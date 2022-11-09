<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\models\OrdersModel; ?>
<?php use mmaurice\cabinet\models\OrdersPropertiesModel; ?>
<?php use mmaurice\cabinet\models\WebUsersModel; ?>

<?php $userId = WebUsersModel::model()->getId(); ?>

<div id="orderList" class="orders u-content">
<?php if ($order) : ?>
    <div class="row">
        <div class="col-12 col-md-6">
            <table>
                <tr>
                    <td><strong>Заявка</strong>:</td>
                    <td><?= $order['id']; ?></td>
                </tr>
                <tr>
                    <td><strong>Тур</strong>:</td>
                    <td><?= (isset($order['tour']) ? $order['tour']['pagetitle'] : '&mdash;'); ?></td>
                </tr>
    <?php $properties = OrdersPropertiesModel::propertiesPrepare($order['properties']); ?>
    <?php if (is_array($properties) and !empty($properties)) : ?>
        <?php foreach ($properties as $key => $value) : ?>
                <tr>
                    <td><strong><?= $key; ?></strong>:</td>
                    <td><?= $value; ?></td>
                </tr>
        <?php endforeach; ?>
    <?php endif; ?>
                <tr>
                    <td><strong>Комментарий</strong>:</td>
                    <td><?= $order['comment']; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-12 col-md-6">
            <table>
                <tr>
                    <td><strong>Статус</strong>:</td>
                    <td><?= $order['status']['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Стоимость</strong>:</td>
                    <td><?= OrdersModel::model()->getOrderPrice($order['id']) ?> руб.</td>
                </tr>
                <tr>
                    <td><strong>Оплачено</strong>:</td>
                    <td><?= OrdersModel::model()->getOrderPayments($order['id']) . ' руб.'; ?></td>
                </tr>
                <tr>
                    <td><strong>Остаток</strong>:</td>
                    <td><?= OrdersModel::model()->getOrderBalancePayments($order['id']) . ' руб.'; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="card__footer">
        <div class="tour-table__item_header text-center">Переписка с менеджером</div>
        <div class="row">
            <div class="col-12 col-md-5">
                <form id="sendMessageForm" action="<?= App::init()->makeUrl('/{lk}/order/message', ['orderId' => $order['id']]) ?>" method="POST">
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Написать сообщение" name="messageText"></textarea>
                        <input type="hidden" name="orderId" value="<?= $order['id']; ?>">
                        <input type="hidden" name="threadId" value="<?= $order['thread']['id']; ?>">
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">
                            Отправить
                        </button>
                    </div>
                </form>
            </div>
    <?php if (is_array($order['thread']['messages']) and !empty($order['thread']['messages'])) : ?>
            <div class="col-12 col-md-7">
                <div class="messages-list">
        <?php foreach ($order['thread']['messages'] as $message) : ?>
                    <div class="messages-list__item">
                        <div class="messages-list__item-status <?= (intval($message['sender']) === intval($userId) ? 'messages-list__item-status_outgoing' : 'messages-list__item-status_incoming'); ?>">
                            <i class="fas <?= (intval($message['sender']) === intval($userId) ? 'fa-long-arrow-alt-left' : 'fa-long-arrow-alt-right'); ?>"></i>
                        </div>
                        <div class="messages-list__item-text">
                            <div class="messages-list__item-from"><?php (!is_null($message['sender']) ? $message['webuser']['attributes']['fullname'] : 'Система'); ?></div>
                            <?= $message['message']; ?>
                        </div>
                        <div class="messages-list__item-date"><?= (!is_null($message['update_date']) ? $message['update_date'] : $message['create_date']); ?></div>
                        <div class="messages-list__item-files"></div>
                    </div>
        <?php endforeach; ?>
                </div>
            </div>
    <?php endif; ?>
        </div>
    </div>
<?php else : ?>
    <p><strong>Запрошенная заявка не найдена!</strong></p>
<?php endif;?>
</div>