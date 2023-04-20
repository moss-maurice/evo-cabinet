<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\models\WebUserAttributesModel as ParentWebUserAttributesModel;
use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\UserRolesModel;

class WebUserAttributesModel extends ParentWebUserAttributesModel
{
    public $relations = [
        'roles' => ['role', [UserRolesModel::class, 'id'], self::REL_ONE],
    ];

    /**
     * Return a user data from `web_user_attributes` by order id.
     *
     * @param int $orderId
     * @return array|null
     */
    public function getDataByOrderId($orderId)
    {
        return OrdersModel::model()->getList([
            'select' => [
                "ua.email as email",
            ],
            'join' => [
                "LEFT JOIN " . static::getFullModelTableName() . " ua ON ua.internalKey = t.user_id",
            ],
            'where' => [
                "t.orderId = '{$orderId}'",
            ],
        ]);
    }
}
