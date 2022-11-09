<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;
use mmaurice\cabinet\models\WebUserThreadFilesModel;
use mmaurice\cabinet\models\WebUserThreadMessagesReadStatusModel;
use mmaurice\cabinet\models\WebUsersModel;

class WebUserThreadMessagesModel extends Model
{
    const ITEM_TYPE_MESSAGE = 'message';
    const ITEM_TYPE_STATUS = 'status';

    const ITEM_STATUS_PUBLISHED = 0;
    const ITEM_STATUS_DELETED = 1;

    public $tableName = 'web_user_thread_messages';

    public $relations = [
        'webuser' => ['sender', [WebUsersModel::class, 'id'], self::REL_ONE],
        'files' => ['id', [WebUserThreadFilesModel::class, 'message_id'], self::REL_MANY/*, [
            'where' => [
                "AND `deleted` = '" . WebUserThreadFilesModel::DELETED . "'",
            ],
        ]*/],
        'read_status' => ['id', [WebUserThreadMessagesReadStatusModel::class, 'message_id'], self::REL_MANY],
        'reply_to' => ['reply_to_message_id', [WebUserThreadMessagesModel::class, 'id'], self::REL_ONE],
    ];

    public function getMessages($threadId)
    {
        return $this->getList([
            'where' => [
                "thread_id = '{$threadId}'",
            ],
        ], true);
    }

    public function addMessage($threadId, $message, $senderId = null, $replyToMessageId = null)
    {
        return $this->createMessage($threadId, $message, $senderId, $replyToMessageId, $type = self::ITEM_TYPE_MESSAGE);
    }

    public function addStatus($threadId, $message, $senderId = null)
    {
        return $this->createMessage($threadId, $message, $senderId, null, $type = self::ITEM_TYPE_STATUS);
    }

    public function createMessage($threadId, $message, $senderId = null, $replyToMessageId = null, $type = self::ITEM_TYPE_MESSAGE)
    {
        $fields = [
            'thread_id' => $threadId,
            'message' => $message,
            'type' => $type,
            'deleted' => WebUserThreadMessagesModel::ITEM_STATUS_PUBLISHED,
        ];

        if (!is_null($senderId) and !empty($senderId) and ($senderId != 0)) {
            $fields['sender'] = $senderId;
        }

        $replyToMessageId = intval($replyToMessageId);

        if (!is_null($replyToMessageId) and ($replyToMessageId !== 0)) {
            $fields['reply_to_message_id'] = $replyToMessageId;
        }

        return $this->insert($fields);
    }

    public function updateMessage($messageId, $message, $senderId = null, $replyToMessageId = null)
    {
        $fields = [
            'message' => $message,
        ];

        if (!is_null($senderId) and !empty($senderId) and ($senderId != 0)) {
            $fields['sender'] = $senderId;
        }

        $replyToMessageId = intval($replyToMessageId);

        if (!is_null($replyToMessageId) and ($replyToMessageId !== 0)) {
            $fields['reply_to_message_id'] = $replyToMessageId;
        }

        return $this->update($fields, "id = '{$messageId}'");
    }

    public function deleteMessage($messageId)
    {
        return $this->update([
            'deleted' => WebUserThreadMessagesModel::ITEM_STATUS_DELETED,
        ], "id = '{$messageId}'");
    }

    public function dropThreadMessages($threadId)
    {
        return $this->update([
            'deleted' => WebUserThreadMessagesModel::ITEM_STATUS_DELETED,
        ], "thread_id = '{$threadId}'");
    }
}
