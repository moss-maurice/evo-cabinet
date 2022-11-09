<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\helpers\DatesHelper; ?>

<?php if (!empty($messages)) : ?>
                    <!-- Widget ThreadMsgList block [ -->
    <?php foreach ($messages as $message) : ?>
        <?php $avatar = App::getPublicWebRoot('/media/user_files' . Core::getImageStructure($message['user']['settings']['userpic']) . DIRECTORY_SEPARATOR . $message['user']['settings']['userpic']); ?>
        <?php if (!file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $avatar) or !is_file($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $avatar)) : ?>
            <?php $avatar = '/assets/snippets/lk/assets/img/cabinet/no_photo.jpg'; ?>
        <?php endif; ?>
                    <div class="message-item">
                        <div class="row">
                            <div class="col-md-2 message-pers">
                                <div class="message-photo">
                                    <a href="<?= $avatar ?>">
                                        <img src="<?= $avatar ?>" alt="">
                                    </a>
                                </div>
                                <div class="message-date"><?= DatesHelper::msgDateFormat($message['date']) ?></div>
                                <div class="message-name"><a href="#"><?= $message['user']['settings']['first-name'] ?></a></div>
                            </div>
                            <div class="col-md-10 message-info">
                                <div class="message-text">
                                    <p><?= $message['message'] ?></p>
        <?php if (!empty($message['files'])) : ?>
                                    <div class="message-docs">
            <?php foreach ($message['files'] as $file) : ?>
                <?php $filePath = App::getPublicWebRoot('/media/user_files' . Core::getImageStructure($file['file_name']) . DIRECTORY_SEPARATOR . $file['file_name']); ?>
                
                                        <div class="message-doc">
                                            <a href="#">
                                                <span class="icon-document"></span>
                                                <strong><?= $file['real_file_name'] ?></strong>
                                            </a>
                                        </div>
            <?php endforeach; ?>
                                    </div>
        <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ] Widget ThreadMsgList block -->
    <?php endforeach; ?>
<?php endif; ?>
