<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;

class WebUserThreadMessagesReadStatusModel extends Model
{
    const ITEM_READ_STATUS_UNREAD = 0;
    const ITEM_READ_STATUS_READ = 1;

    public $tableName = 'web_user_thread_messages_read_status';

    public function setMessageStatusRead($messageId, $userId)
    {
        return $this->setMessageStatus($messageId, $userId, self::ITEM_READ_STATUS_READ);
    }

    public function setMessageStatusUnread($messageId, $userId)
    {
        return $this->setMessageStatus($messageId, $userId, self::ITEM_READ_STATUS_UNREAD);
    }

    public function setMessageStatus($messageId, $userId, $status = self::ITEM_READ_STATUS_UNREAD)
    {
        if ($this->getItem([
            'where' => [
                'message_id' => $messageId,
                'webuser' => $userId,
            ],
        ])) {
            return $this->update([
                'status' => self::ITEM_READ_STATUS_READ,
            ], "message_id = '{$messageId}' AND webuser = '{$userId}'");
        }

        return $this->insert([
            'status' => self::ITEM_READ_STATUS_READ,
            'message_id' => $messageId,
            'webuser' => $userId,
        ]);
    }
}
