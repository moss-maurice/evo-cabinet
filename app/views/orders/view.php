<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\models\OrdersModel; ?>
<?php use mmaurice\cabinet\models\WebUsersModel; ?>
<?php use mmaurice\cabinet\widgets\PageTitleWidget; ?>
<?php use mmaurice\cabinet\helpers\DatesHelper; ?>
<?php use mmaurice\cabinet\core\helpers\FormatHelper; ?>
<?php use mmaurice\cabinet\models\OrdersStatusesModel; ?>

<?php $userId = WebUsersModel::model()->getId(); ?>
<?php global $modx; ?>

<?= PageTitleWidget::init([
    'title' => 'Заявка №' . $order['id'],
])->run(); ?>

<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header h5 text-center">
                Данные заявки
            </div>
            <div class="card-body p-0">
                <div class="row mx-5 py-3">
                    <?php $columns = 6; ?>
                    <?php if (!is_null($order['update_date'])) : ?>
                    <?php $columns = 4; ?>
                    <?php endif; ?>
                    <div class="form-group col-sm-<?= $columns; ?> py-3 px-0 m-0">
                        <label class="m-0 h6 font-weight-normal">Заявка</label>
                        <div class="h5 font-weight-normal">№ <?= $order['id']; ?></div>
                    </div>
                    <div class="form-group col-sm-<?= $columns; ?> py-3 px-0 m-0">
                        <label class="m-0 h6 font-weight-normal">Дата создания</label>
                        <div class="h5 font-weight-normal"><?= DatesHelper::getSpelledDate($order['create_date']); ?>
                        </div>
                    </div>
                    <?php if (!is_null($order['update_date'])) : ?>
                    <div class="form-group col-sm-<?= $columns; ?> py-3 px-0 m-0">
                        <label class="m-0 h6 font-weight-normal">Дата обновления</label>
                        <div class="h5 font-weight-normal"><?= DatesHelper::getSpelledDate($order['update_date']); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($order['comment'])) : ?>
                <div class="form-group mx-5 py-3 px-0">
                    <label class="m-0 h6 font-weight-normal">Комментарий</label>
                    <div class="h5 font-weight-normal"><?= $order['comment']; ?></div>
                </div>
                <?php endif; ?>
            </div>

            <?php if(isset($order['tour'])) : ?>
            <div class="card-header border-bottom-0 px-0 py-3">
                <div class="mx-5">
                    <div class="m-0 h6 font-weight-normal">Тур</div>
                    <div class="row">
                        <div class="h5 col-sm-9">
                            <a href="<?= $modx->makeUrl($order['tour']['id']); ?>" target="blank">
                                <?= $order['tour']['pagetitle']; ?>
                            </a>
                        </div>
                        <div class="h6 col-sm-3 pt-1 text-right font-weight-bold">
                            <?= OrdersModel::model()->getOrderPrice($order['id']) ?> ₽
                        </div>
                        <div class="h6 col-sm-9 pl-5 font-weight-normal">Оплачено</div>
                        <div class="h6 col-sm-3 pt-1 text-right font-weight-normal ">
                            <?= OrdersModel::model()->getOrderTotalPayments($order['id']) . ' ₽'; ?>
                        </div>
                        <div class="h6 col-sm-9 pl-5 font-weight-normal">Остаток</div>
                        <div class="h6 col-sm-3 pt-1 text-right font-weight-normal ">
                            <?= OrdersModel::model()->getOrderBalancePayments($order['id']) . ' ₽'; ?>
                        </div>
                    </div>
                    <?php if (in_array(intval($order['status']['id']), [OrdersStatusesModel::STATUS_CONFIRMED])) : ?>
                    <?php if (OrdersModel::model()->getOrderBalancePayments($order['id']) > 0) : ?>
                    <?php if (is_array($paymentsPlugins) and !empty($paymentsPlugins)) : ?>
                    <div class="row d-flex justify-content-center">
                        <?php foreach ($paymentsPlugins as $plugin) : ?>
                        <div class="col-auto p-3">
                            <?php if ($plugin['name'] === 'Sberbank Payment') : ?>
                            {{payButton ?
                                &amount=`<?= OrdersModel::model()->getOrderBalancePayments($order['id']); ?>`
                                &tmpl=`button`
                                &buttonCaption=`Оплата Онлайн`
                            }}
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (is_array($statuses) and !empty($statuses)) : ?>
            <div class="row mx-5 pt-5 pb-3">
                <div class="col-lg-12 p-0">
                    <div class="horizontal-timeline">
                        <ul class="list-inline items d-flex justify-content-between">
                            <?php foreach($statuses as $status) : ?>
                            <?php if ($status['id'] <= intval($order['status']['id'])) : ?>
                            <?php if ($status['id'] < intval($order['status']['id'])) : ?>
                            <li class="list-inline-item items-list text-left">
                                <p class="py-1 px-2 rounded text-white bg-success"><?= $status['name']; ?></p>
                            </li>
                            <?php else : ?>
                            <li class="list-inline-item items-list text-left">
                                <p class="py-1 px-2 rounded text-white bg-primary"><?= $status['name']; ?></p>
                            </li>
                            <?php endif; ?>
                            <?php else : ?>
                            <li class="list-inline-item items-list text-right" style="margin-right: 8px;">
                                <p class="pt-1" style="margin-right: -8px;"><?= $status['name']; ?></p>
                            </li>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header h5 text-center">Общение с менеджером</div>
            <div class="card-body p-0 m-0">
                <div class="chat">
                    <div class="mx-5 px-3 py-3">
                        <?php if ($thread) : ?>
                        <?php if (is_array($thread['messages']) and !empty($thread['messages'])) : ?>
                        <?php foreach ($thread['messages'] as $message) : ?>
                        <?php if ($message['deleted'] == 1) : ?><div class="row justify-content-center py-2"
                            rel-data-id="<?= $message['id']; ?>">
                            <div class="col-sm-9 py-2 px-3 text-center">
                                <small class="py-2 font-italic">Сообщение удалено ...</small>
                            </div>
                        </div>
                        <?php else: ?>
                        <?php if ($message['type'] === 'status') : ?>
                        <div class="row justify-content-center py-2" rel-data-id="<?= $message['id']; ?>">
                            <div class="col-sm-9 py-2 px-3 text-center">
                                <small class="py-2 font-italic"><?= $message['message']; ?></small>
                            </div>
                        </div>
                        <?php else: ?>
                        <?php if (intval($message['sender']) !== $userId) : ?>
                        <div class="row justify-content-start py-2" rel-data-id="<?= $message['id']; ?>">
                            <div class="col-sm-9 bg-secondary text-white rounded py-2 px-3">
                                <div class="font-weight-bold" style="font-size: 12px;">Менеджер:</div>
                                <?php if ($message['reply_to']):  ?>
                                <div class="pb-1 px-2 mt-2 mx-2 border-left border-secondary font-italic">
                                    <small>
                                        <?= $message['reply_to']['message']; ?>
                                    </small>
                                </div>
                                <?php endif; ?>
                                <div class="py-3 message"><?= $message['message']; ?></div>
                                <small class="text-right d-block font-weight-bold" style="font-size: 12px;">
                                    <span class="reply pr-2">Ответить</span>
                                    <?= FormatHelper::dateConvert($message['create_date'], 'Y-m-d H:i:s', 'd.m.Y, H:i'); ?>
                                    <?php /*if (!is_null($message['update_date'])) : ?>
                                    (изм.)
                                    <?php endif;*/ ?>
                                </small>
                            </div>
                        </div>
                        <?php else : ?>
                        <div class="row justify-content-end py-2" rel-data-id="<?= $message['id']; ?>">
                            <div class="col-sm-9 bg-primary text-white rounded py-2 px-3">
                                <div class="font-weight-bold" style="font-size: 12px;">Вы:</div>
                                <?php if ($message['reply_to']):  ?>
                                <div class="pb-1 px-2 mt-2 mx-2 border-left border-white font-italic">
                                    <small>
                                        <?= $message['reply_to']['message']; ?>
                                    </small>
                                </div>
                                <?php endif; ?>
                                <div class="py-3 message"><?= $message['message']; ?></div>
                                <small class="text-right d-block font-weight-bold" style="font-size: 12px;">
                                    <span class="reply pr-2">Ответить</span>
                                    <span class="edit pr-2">Изменить</span>
                                    <span class="delete pr-2">Удалить</span>
                                    <?= FormatHelper::dateConvert($message['create_date'], 'Y-m-d H:i:s', 'd.m.Y, H:i'); ?>
                                    <?php /*if (!is_null($message['update_date'])) : ?>
                                    (изм.)
                                    <?php endif;*/ ?>
                                </small>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <?php else: ?>
                        <div class="h5 font-weight-normal text-center m-5 py-5">
                            Если у вас есть вопрос, можете задать его нам!
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-footer p-3">
                <form action="<?= App::init()->makeUrl('/{lk}/order/message') ?>" method="post" id="message">
                    <input type="hidden" name="orderId" value="<?= $order['id']; ?>" />
                    <input type="hidden" name="threadId" value="<?= $thread ? $thread['id'] : ''; ?>" />
                    <input type="hidden" name="replyTo" value="" />
                    <input type="hidden" name="messageId" value="" />
                    <input type="hidden" name="removeId" value="" />

                    <div id="reply-board" class="pb-1 pl-3 pr-4 m-2 mb-3 border-left font-italic d-none">
                        <small>asdasdasdasdasdsadsada</small>
                    </div>
                    <div class="input-group">
                        <input type="text" name="messageText" class="bs form-control" placeholder="Ваше сообщение..." />
                        <div class="input-group-append">
                            <span id="cancel" class="bs btn btn-outline-secondary d-none" type="submit">Отмена</span>
                            <button class="bs btn btn-outline-success" type="submit">Отправить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>