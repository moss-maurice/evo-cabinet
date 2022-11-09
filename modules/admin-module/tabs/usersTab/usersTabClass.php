<?php

use mmaurice\cabinet\models\OrdersModel;
use mmaurice\cabinet\models\OrdersPersonsModel;
use mmaurice\cabinet\models\OrdersStatusesModel;
use mmaurice\cabinet\models\WebUserSettingsModel;
use mmaurice\cabinet\models\CountriesModel;
use mmaurice\cabinet\models\WebUsersModel;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\helpers\FormatHelper;

class UsersTabClass extends TabClass
{
    public $title = 'Пользователи';
    public $description = 'Список пользователей ЛК.';
    public $orderPosition = 20;

    public function actionIndex()
    {
        return $this->actionList();
    }

    public function actionList()
    {
        $page = intval(App::request()->extractPost('page', 1));
        $limit = intval(App::request()->extractPost('limit', 25));

        if ($page === 0) {
            $limit = null;
            $page = 1;
        }

        $fields = [
            'roleId' => App::request()->extractPost('roleId'),
            'agency' => App::request()->extractPost('agency'),
            'login' => App::request()->extractPost('login'),
            'email' => App::request()->extractPost('email'),
            'phone' => App::request()->extractPost('phone'),
            'field' => App::request()->extractPost('field'),
            'direction' => App::request()->extractPost('direction', 'DESC'),
        ];

        $usersList = WebUsersModel::model()->getUsersList($page, $limit, $fields);

        if ($usersList and isset($usersList['usersList']) and is_array($usersList['usersList']) and !empty($usersList['usersList'])) {
            $usersList['usersList'] = array_map(function ($userItem) {
                $userItem['settings'] = WebUserSettingsModel::settingPrepare($userItem['settings']);

                return $userItem;
            }, $usersList['usersList']);
        } else {
            $usersList['usersList'] = [];
        }

        return $this->render('list', array_merge([
            'tabName' => trim(App::request()->extractPost('tabName', $this->tabName)),
            'tabMethod' => trim(App::request()->extractPost('method', 'index')),
            'usersList' => $usersList['usersList'],
            'pages' => $usersList['pages'],
            'page' => $page,
            'limit' => $usersList['limit'],
            'directions' => [
                App::request()->extractPost('field', 'name') => App::request()->extractPost('direction', 'ASC')
            ],
        ], $fields));
    }

    public function actionRemove()
    {
        WebUsersModel::model()->deleteUser(App::request()->extractPost('item_id'));

        return $this->actionList();
    }

    public function actionView($unSavedFields = [])
    {
        $itemId = App::request()->extractPost('item_id');

        if ($itemId) {
            $itemId = intval($itemId);

            $userItem = WebUsersModel::model()->getItem([
                'where' => [
                    "t.id = '{$itemId}'",
                ],
            ], true);

            $userItem['settings'] = WebUserSettingsModel::settingPrepare($userItem['settings']);

            if (is_array($unSavedFields) and !empty($unSavedFields)) {
                foreach ($unSavedFields as $key => $value) {
                    if (array_key_exists($key, $userItem['attributes']) and !is_null($value) and ($userItem['attributes'][$key] !== $value)) {
                        $userItem['attributes'][$key] = $value;
                    }

                    if (array_key_exists($key, $userItem['settings']) and !is_null($value) and ($userItem['settings'][$key] !== $value)) {
                        $userItem['settings'][$key] = $value;
                    }
                }
            }

            return $this->render('view', array(
                'itemId' => $itemId,
                'tabName' => App::request()->extractPost('tabName', $this->tabName),
                'countries' => CountriesModel::model()->getList(),
                'unSavedFields' => $unSavedFields,
                'userItem' => $userItem,
            ));
        }

        return $this->actionList();
    }

    public function actionUpdate()
    {
        $fields = [
            'last_name' => App::request()->extractPost('lastName'),
            'first_name' => App::request()->extractPost('firstName'),
            'middle_name' => App::request()->extractPost('middleName'),
            'dob' => (!is_null(App::request()->extractPost('dob')) ? FormatHelper::dateToTimestampConvert(App::request()->extractPost('dob') . ' 00:00:00', 'd-m-Y H:i:s') : null),
            'phone' => App::request()->extractPost('phone'),
            'mobilephone' => App::request()->extractPost('mobilephone'),
            'email' => App::request()->extractPost('email'),
            'fax' => App::request()->extractPost('fax'),
            'sale' => App::request()->extractPost('sale'),
            'agency_status' => App::request()->extractPost('agencyStatus'),
            'country' => App::request()->extractPost('country'),
            'state' => App::request()->extractPost('state'),
            'city' => App::request()->extractPost('city'),
            'street' => App::request()->extractPost('street'),
            'password' => App::request()->extractPost('password'),
            'password_retype' => App::request()->extractPost('password'),
            'comission' => App::request()->extractPost('comission'),
            'comission_chld' => App::request()->extractPost('comissionChld'),
            'agency' => App::request()->extractPost('agency'),
            'agency_zip' => App::request()->extractPost('agencyZip'),
            'agency_country' => App::request()->extractPost('agencyCountry'),
            'agency_state' => App::request()->extractPost('agencyState'),
            'agency_city' => App::request()->extractPost('agencyCity'),
            'agency_street' => App::request()->extractPost('agencyStreet'),
            'agency_legal_address' => App::request()->extractPost('agencyLegalAddress'),
            'agency_inn' => App::request()->extractPost('agencyInn'),
            'agency_kpp' => App::request()->extractPost('agencyKpp'),
            'agency_ogrn' => App::request()->extractPost('agencyOgrn'),
            'agency_rs' => App::request()->extractPost('agencyRs'),
            'agency_ks' => App::request()->extractPost('agencyKs'),
            'agency_bank' => App::request()->extractPost('agencyBank'),
            'agency_bik' => App::request()->extractPost('agencyBik'),
            'agency_type' => App::request()->extractPost('agencyType'),
        ];

        $fields = array_filter($fields, function($value, $key) {
            if (is_null($value)) {
                return false;
            }

            return true;
        }, ARRAY_FILTER_USE_BOTH);

        WebUsersModel::model()->updateProfile(App::request()->extractPost('item_id'), $fields);

        return $this->actionView();
    }

    public function actionUpdateRole()
    {
        WebUsersModel::model()->updateProfile(App::request()->extractPost('item_id'), [
            'role' => App::request()->extractPost('role'),
            'type' => App::request()->extractPost('type'),
            'agency_status' => App::request()->extractPost('agencyStatus'),
        ]);

        return $this->actionView();
    }

    public function actionChangeStatus()
    {
        WebUsersModel::model()->updateProfile(App::request()->extractPost('item_id'), [
            'agency_status' => App::request()->extractPost('agencyStatus'),
        ]);

        return $this->actionList();
    }

    public function actionChangeStatusProfile()
    {
        WebUsersModel::model()->updateProfile(App::request()->extractPost('item_id'), [
            'agency_status' => App::request()->extractPost('agencyStatus'),
        ]);

        return $this->actionView();
    }
}
