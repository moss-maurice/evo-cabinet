<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\models\WebUsersModel as ParentWebUsersModel;
use mmaurice\cabinet\helpers\ImagesHelper;
use mmaurice\cabinet\models\UserRolesModel;
use mmaurice\cabinet\models\WebUserAttributesModel;
use mmaurice\cabinet\models\OrdersPaymentsTransactionsModel;
use mmaurice\cabinet\models\WebUserSettingsModel;

class WebUsersModel extends ParentWebUsersModel
{
    public $relations = [
        'settings' => ['id', [WebUserSettingsModel::class, 'webuser'], self::REL_MANY],
        'attributes' => ['id', [WebUserAttributesModel::class, 'internalKey'], self::REL_ONE],
    ];

    public function updateProfile($userId, $inputFields = array())
    {
        $result = false;
        $userAttributesModel = new WebUserAttributesModel();
        $userSettingsModel = new WebUserSettingsModel();

        if (in_array('password', array_keys($inputFields)) and in_array('password_retype', array_keys($inputFields))) {
            $this->updatePass($userId, $inputFields['password'], $inputFields['password_retype']);
            unset($inputFields['password']);
            unset($inputFields['password_retype']);
        }

        $fields = [];
        if (array_key_exists('first_name', $inputFields) and array_key_exists('last_name', $inputFields) and array_key_exists('middle_name', $inputFields)) {
            $fullname = '';
            if (array_key_exists('last_name', $inputFields)) {
                $fullname .= $inputFields['last_name'];
            }
            if (array_key_exists('first_name', $inputFields)) {
                $fullname .= ' ' . $inputFields['first_name'];
            }
            if (array_key_exists('middle_name', $inputFields)) {
                $fullname .= ' ' . $inputFields['middle_name'];
            }
            $fullname = trim(str_replace('  ', ' ', $fullname), ' ');
            $inputFields['fullname'] = $fullname;
        }
        if (array_key_exists('role', $inputFields) and !empty($inputFields['role'])) {
            $fields['role'] = $this->escape($inputFields['role']);
            unset($inputFields['role']);
        }
        if (array_key_exists('fullname', $inputFields) and !empty($inputFields['fullname'])) {
            $fields['fullname'] = $this->escape($inputFields['fullname']);
            unset($inputFields['fullname']);
        }
        if (array_key_exists('email', $inputFields) and !empty($inputFields['email'])) {
            $fields['email'] = $this->escape($inputFields['email']);
            unset($inputFields['email']);
        }
        if (array_key_exists('phone', $inputFields)) {
            $fields['phone'] = $this->escape($inputFields['phone']);
            unset($inputFields['phone']);
        }
        if (array_key_exists('dob', $inputFields)) {
            $fields['dob'] = $this->escape($inputFields['dob']);
            unset($inputFields['dob']);
        }
        if (array_key_exists('mobilephone', $inputFields)) {
            $fields['mobilephone'] = $this->escape($inputFields['mobilephone']);
            unset($inputFields['mobilephone']);
        }
        if (array_key_exists('gender', $inputFields)) {
            $fields['gender'] = $this->escape($inputFields['gender']);
            unset($inputFields['gender']);
        }
        if (array_key_exists('country', $inputFields)) {
            $fields['country'] = $this->escape($inputFields['country']);
            unset($inputFields['country']);
        }
        if (array_key_exists('street', $inputFields)) {
            $fields['street'] = $this->escape($inputFields['street']);
            unset($inputFields['street']);
        }
        if (array_key_exists('city', $inputFields)) {
            $fields['city'] = $this->escape($inputFields['city']);
            unset($inputFields['city']);
        }
        if (array_key_exists('state', $inputFields)) {
            $fields['state'] = $this->escape($inputFields['state']);
            unset($inputFields['state']);
        }
        if (array_key_exists('zip', $inputFields)) {
            $fields['zip'] = $this->escape($inputFields['zip']);
            unset($inputFields['zip']);
        }
        if (array_key_exists('fax', $inputFields)) {
            $fields['fax'] = $this->escape($inputFields['fax']);
            unset($inputFields['fax']);
        }
        if (!empty($fields)) {
            $fields['editedon'] = time();

            if ($userAttributesModel->update($fields, "internalKey = '" . $userId . "'")) {
                $result = true;
            }
        }

        if (is_array($inputFields) and !empty($inputFields)) {
            foreach ($inputFields as $inputFieldsKey => $inputFieldsValue) {
                $userSetting = $userSettingsModel->select('*', "webuser = '" . $userId . "' AND setting_name = '" . $inputFieldsKey . "'");

                if ($userSettingsModel->getRecordCount($userSetting) > 0) {
                    if ($userSettingsModel->update(array(
                        'setting_value' => $userSettingsModel->escape($inputFieldsValue),
                    ), "webuser = '" . $userId . "' AND setting_name = '" . $inputFieldsKey . "'")) {
                        $result = true;
                    }
                } else {
                    $userSettingsModel->insert(array(
                        'webuser' => $userId,
                        'setting_name' => $inputFieldsKey,
                        'setting_value' => $userSettingsModel->escape($inputFieldsValue),
                    ));
                }
            }
        }

        OrdersPaymentsTransactionsModel::applyUserOrdersPersonalSale($userId);

        return $result;
    }

    public function isAgent()
    {
        if ($this->isLogged()) {
            if (intval($this->getAttribute('role', UserRolesModel::ROLE_ID_AGENCY)) === UserRolesModel::ROLE_ID_AGENCY) {
                if ($this->getSetting('agency_status', 'false') === 'true') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Метод получения аватара пользователя
     *
     * @return void
     */
    public function getAvatar()
    {
        $userAvatar = $this->getSetting('userpic', '');
        $result = App::getPublicWebRoot('/assets/img/cabinet/no_photo.jpg');
        if (!empty($userAvatar) and !is_null($userAvatar)) {
            $result = App::getPublicWebRoot('/media/user_files' . ImagesHelper::getImageStructure($userAvatar) . '/' . $userAvatar);
        }
        return $result;
    }

    public function socialAuth()
    {
        $result = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
        $userData = json_decode($result, true);
        if (is_array($userData) and !empty($userData)) {
            $userData['login'] = (string) strtolower($this->kyr2Lat($userData['first_name'] . ' ' . $userData['last_name'] . ' ' . $userData['uid']));
            $login = $this->escape($userData['login']);
            if ($this->hasUser($login)) {
                $webUser = $this->select('id, username', "username='" . $login . "'");
                if ($this->getRecordCount($webUser)) {
                    $row = $this->getRow($webUser);
                    $this->loadUserData(intval($row['id']));
                }
                return $this->isLogged();
            } else {
                $password = $this->passGenerate();
                return $this->register($login, $password, $password, 'none@none.none', array(
                    'first-name' => $userData['first_name'],
                    'last-name' => $userData['last_name'],
                    'profile' => $userData['profile'],
                    'uid' => $userData['uid'],
                    'city' => $userData['original_city'],
                    'provider' => $userData['network'],
                    'identity' => $userData['identity'],
                ));
            }
        }
    }
}
