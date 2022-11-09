<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\WebUserThreadMessagesModel;

class WebUserThreadsModel extends Model
{
    const THREAD_DEFAULT_ORDER = 0;

    public $tableName = 'web_user_threads';

    public $relations = [
        'messages' => ['id', [WebUserThreadMessagesModel::class, 'thread_id'], self::REL_MANY],
        'lastMessage' => ['id', [WebUserThreadMessagesModel::class, 'thread_id'], self::REL_ONE, [
            'where' => [
                "AND wutm.deleted = '" . WebUserThreadMessagesModel::ITEM_STATUS_PUBLISHED . "'",
            ],
            'order' => [
                "t.create_date DESC",
            ],
        ]],
        'unreadMessages' =>  ['id', [WebUserThreadMessagesModel::class, 'thread_id'], self::REL_MANY, [
            ''
        ]],
    ];

    public function getUserThreads($userId)
    {
        return $this->getList([
            'where' => [
                "webuser = '{$userId}'",
            ],
        ], true);
    }

    public function getOrder()
    {
        return OrdersModel::model()->getItem([
            'where' => [
                "id = '{$this->order_id}'",
            ],
        ], true);
    }

    public function getOrderThread($orderId)
    {
        return $this->getItem([
            'where' => [
                "order_id = '{$orderId}'",
            ],
        ], true);
    }

    public function getThreadOrder($threadId)
    {
        $thread = $this->getItem([
            'where' => [
                "id = '{$threadId}'",
            ],
        ], true);

        if ($thread) {
            return OrdersModel::model()->getItem([
                'where' => [
                    "id = '{$thread['order_id']}'",
                ],
            ], true);
        }

        return null;
    }

    public function addThread($orderId, $subject, $userId)
    {
        return $this->insert([
            'order_id' => $orderId,
            'subject' => $subject,
            'webuser' => $userId,
        ]);
    }

    public function updateThreadSubject($threadId, $subject)
    {
        return $this->update([
            'subject' => $subject,
        ], "id = '{$threadId}'");
    }

    public function deleteThread($threadId)
    {
        $messageModel = new WebUserThreadMessagesModel;

        $messageModel->dropThreadMessages($threadId);

        return $this->delete("id = '{$threadId}'");
    }

    public function getUserUnreadMessages($userId = 0)
    {
        $where = [
            "wutm.sender != '{$userId}'",
            "AND wutm.deleted = '" . WebUserThreadMessagesModel::ITEM_STATUS_PUBLISHED . "'",
            "AND wutmrs.id IS NULL",
        ];

        if (intval($userId) > 0) {
            $where[] = "AND wut.webuser = '{$userId}'";
        }

        $threads = $this->getList([
            'alias' => 'wut',
            'select' => [
                "wut.*",
            ],
            'join' => [
                "LEFT JOIN evo_web_user_thread_messages wutm ON wutm.thread_id = wut.id",
                "LEFT JOIN evo_web_user_thread_messages_read_status wutmrs ON wutmrs.message_id = wutm.id AND wutmrs.webuser = '{$userId}'",
            ],
            'where' => $where,
            'group' => [
                "wut.id",
            ],
            'order' => [
                "wutm.create_date DESC",
            ],
        ]);

        if ($threads) {
            $threadsIds = [];

            foreach ($threads as $thread) {
                $threadsIds[] = intval($thread['id']);
            }

            if (!empty($threadsIds)) {
                return $this->getList([
                    'alias' => 'wut',
                    'where' => [
                        "wut.id IN ('" . implode("','", $threadsIds) . "')",
                    ],
                ], true);
            }
        }

        return false;
    }
}
