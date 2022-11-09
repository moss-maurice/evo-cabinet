<?php

namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\widgets\WidgetPrototype;
use mmaurice\cabinet\models\WebUsersModel;

/**
 * Класс виджета написания сообщения
 */
class ThreadMsgListWidget extends WidgetPrototype
{
    public $threadId = null;

    /**
     * Метод основной логики виджета
     *
     * @return void
     */
    public function run()
    {
        if (!is_null($this->threadId) and ((integer) $this->threadId > 0)) {
            $messagesList = MessagesComponent::model()->getThreadMessages($this->threadId);

            if (is_array($messagesList) and !empty($messagesList)) {
                foreach ($messagesList as $key => $messageItem) {
                    MessagesComponent::model()->readMessage($this->threadId, $messageItem['id']);

                    $messagesList[$key]['user'] = WebUsersModel::model()->getUserData((integer) $messageItem['sender']);
                    $messagesList[$key]['files'] = MessagesComponent::model()->getMessageFiles((integer) $messageItem['id']);
                }
            }

            return $this->render('index', [
                'messages' => serialize($messagesList),
            ]);
        }
    }
}
