<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\models\WebUserAttributesModel as ParentWebUserAttributesModel;
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
        $ordersTable = $this->getFullTableName('tours_orders');
        $usersAttrTable = $this->getFullTableName($this->tableName);

        $resource = $this->query(
            "SELECT `users_attr_table`.`email` as email FROM {$ordersTable} as orders_table
                JOIN {$usersAttrTable} as users_attr_table ON `orders_table`.`user_id` = `users_attr_table`.`internalKey`
            WHERE `orders_table`.`id` = {$orderId}"
        );

        $result = [];
        while ($row = $this->db->getRow($resource)) {
            $result[] = $row;
        }

        return $result;
    }
}
