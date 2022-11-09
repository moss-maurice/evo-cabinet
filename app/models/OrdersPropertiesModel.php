<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\Model;

class OrdersPropertiesModel extends Model
{
    public $tableName = 'orders_properties';

    public static function propertiesPrepare(array $properties = null)
    {
        if (is_array($properties) and !empty($properties)) {
            return array_combine(array_map(function ($value) {
                return $value['key'];
            }, $properties), array_map(function ($value) {
                return $value['value'];
            }, $properties));
        }

        return [];
    }
}
