<?php use mmaurice\cabinet\models\WebUserThreadMessagesModel; ?>
<?php use mmaurice\cabinet\models\WebUserThreadFilesModel; ?>
<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\core\helpers\FormatHelper; ?>

<?php $thread = $threadModel->getOrderThread($orderId); ?>
<?php $threadMessagesModel = new WebUserThreadMessagesModel; ?>
<?php $threadMessages = $threadMessagesModel->getList([
    'where' => [
        "`thread_id` = '{$thread['id']}'",
    ],
    'order' => [
        "t.create_date DESC",
        "t.id DESC",
    ],
], true); ?>

<?php if ($thread) : ?>

<link rel="stylesheet" type="text/css" href="<?= App::getPublicWebRoot('/assets/libs/dropzone-5.9.3/min/dropzone.min.css'); ?>?v=<?= time(); ?>">
<link rel="stylesheet" type="text/css" href="<?= App::getPublicWebRoot('/widgets/views/threadLine/assets/styles/style.css'); ?>?v=<?= time(); ?>">

<hr />
<h3>Комментарии</h3>
<div class="row mb-4">
    <div class="col-12 col-lg-6">
        <input type="hidden" name="message-id" value="">
        <input type="hidden" name="sender-id" value="0">
        <input type="hidden" name="reply-to-message-id" value="">
        <div class="d-none message-quote">
            <span class="text-danger lk-module-message-quote-cancel-button">
                <i class="fa fa-close fa-xs"></i>
            </span>
            <span class="text"></span>
        </div>
        <textarea name="message" rows="5" placeholder="Текст сообщения"></textarea>
        <div class="files-control d-block">
            <form class="dropzone needsclick" id="media-upload" action="/upload">
                <div class="dz-message needsclick">Перетащите файл в это поле или кликните мышкой для загрузки файла</div>
            </form>
        </div>
        <div class="add-control d-block">
            <span class="btn btn-success lk-module-thread-add-message-button col-12" rel-method="addMessage">Отправить</span>
        </div>
        <div class="edit-control d-none">
            <span class="btn lk-module-thread-cancel-edit-message-button col-12 col-lg-12">Отменить</span>
            <span class="btn btn-success lk-module-thread-update-message-button col-12 col-lg-12" rel-method="updateMessage">Обновить</span>
        </div>
    </div>
    <div class="col-12 col-lg-6">
    <?php if (is_array($threadMessages) and !empty($threadMessages)) : ?>
            <table class="table data thread-list mb-4" cellpadding="1" cellspacing="1">
                <tbody>
        <?php foreach ($threadMessages as $message) : ?>
                        <tr rel-message-id="<?= $message['id']; ?>" rel-sender-id="<?= $message['sender']; ?>" rel-message="<?= $message['message']; ?>" rel-reply-to-message-id="<?= (!is_null($message['reply_to_message_id']) ? $message['reply_to_message_id'] : ''); ?>" class="thread-line<?= (($message['type'] === WebUserThreadMessagesModel::ITEM_TYPE_MESSAGE) ? ' message' . ((intval($message['sender']) === 0) ? ' outcoming' : ' incoming') : ' status'); ?><?= (intval($message['deleted']) === WebUserThreadMessagesModel::ITEM_STATUS_DELETED ? ' remove' : '') ?>">
                            <td>
                                <span>
                                    <span class="message"><?= $message['message']; ?></span>
                                    <br />
            <?php if (is_array($message['files']) and !empty($message['files'])) : ?>
                                    <ul class="files">
                <?php foreach ($message['files'] as $file) : ?>
                    <?php if (intval($file['deleted']) === 0) : ?>
                                        <li class="available" rel-file-id="<?= $file['id']; ?>" rel-file-path="/<?= $file['real_file_name']; ?>" rel-file-size="<?= $file['size']; ?>" rel-file-name="<?= $file['file_name']; ?>" rel-file-type="<?= $file['mime']; ?>">
                                            <a href="/<?= $file['real_file_path']; ?>" target="_blank" title="<?= $file['file_name']; ?>"><i class="fa fa-cloud-download-alt"></i> <?= $file['file_name']; ?></a>
                                        </li>
                    <?php else : ?>
                                        <li class="unavailable">
                                            <span><i class="fa fa-cloud-download-alt"></i> <?= $file['file_name']; ?></span>
                                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                                    </ul>
            <?php endif; ?>
                                    <span class="controls">
            <?php if (($message['type'] === WebUserThreadMessagesModel::ITEM_TYPE_MESSAGE) and (intval($message['deleted']) === WebUserThreadMessagesModel::ITEM_STATUS_PUBLISHED)) : ?>
                                            <a class="reply lk-module-thread-reply-message-button">Ответить</a>
                                            <a class="edit lk-module-thread-edit-message-button">Изменить</a>
                                            <a class="delete lk-module-thread-delete-message-button" rel-method="deleteMessage">Удалить</a>
            <?php endif; ?>
                                        <span class="date">
                                            <?= FormatHelper::dateConvert($message['create_date'], 'Y-m-d H:i:s', 'd.m.Y, H:i'); ?>
            <?php if (!is_null($message['update_date'])) : ?>
                <?php if (intval($message['deleted']) === WebUserThreadMessagesModel::ITEM_STATUS_DELETED) : ?>
                                            (уд.)
                <?php else : ?>
                                            (изм.)
                <?php endif; ?>
            <?php endif; ?>
                                        </span>
                                    </span>
                                </span>
                            </td>
                        </tr>
        <?php endforeach; ?>
                </tbody>
            </table>
    <?php endif; ?>
    </div>
</div>

<script src="<?= App::getPublicWebRoot('/assets/libs/dropzone-5.9.3/ru.dropzone.js'); ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= App::getPublicWebRoot('/widgets/views/threadLine/assets/scripts/script.js'); ?>?v=<?= time(); ?>" type="text/javascript"></script>

<?php endif; ?>