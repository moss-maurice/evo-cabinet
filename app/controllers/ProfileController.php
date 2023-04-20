<?php

namespace mmaurice\cabinet\controllers;

use mmaurice\cabinet\components\ControllerComponent;
use mmaurice\cabinet\controllers\MailerController;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\CountriesModel;
use mmaurice\cabinet\models\UserRolesModel;
use mmaurice\cabinet\models\WebUsersModel;

class ProfileController extends ControllerComponent
{
    public function actionIndex()
    {
        $this->checkAccess('/{lk}/login/');
        $this->checkRole('all', '/login');

        $countries = CountriesModel::model()->getList();

        $this->render('index', [
            'countries' => $countries
        ]);
    }

    public function actionUpdate()
    {
        $this->checkAccess('/{lk}/login/');
        $this->checkRole('all', '/login');

        $userModel = new WebUsersModel();

        /**
         * Common fields.
         */
        $userId = intval(App::request()->extractPost('user-id'));
        $role = intval(App::request()->extractPost('user-type-radio'));

        $userData = WebUsersModel::model()->getUserData($userId);

        /**
         * user fields.
         */
        $password = App::request()->extractPost('password', '');
        $passwordRetype = App::request()->extractPost('password-retype', '');
        $firstName = App::request()->extractPost('first_name', '');
        $lastName = App::request()->extractPost('last_name', '');
        $middleName = App::request()->extractPost('middle_name', '');
        $gender = App::request()->extractPost('gender', '');
        $email = App::request()->extractPost('email', '');
        $phone = App::request()->extractPost('phone', '');
        $mobilePhone = App::request()->extractPost('mobilephone', '');
        $fax = App::request()->extractPost('fax', '');
        $country = App::request()->extractPost('country', '');
        $state = App::request()->extractPost('state', '');
        $city = App::request()->extractPost('city', '');
        $street = App::request()->extractPost('street', '');
        $zip = App::request()->extractPost('zip', '');

        /**
         * Agencis fields.
         */
        $agency = App::request()->extractPost('agency-agency', '');
        $zipAgency = App::request()->extractPost('agency-zip', '');
        $countryAgency = App::request()->extractPost('agency-country', '');
        $regionAgency = App::request()->extractPost('agency-region', '');
        $cityAgency = App::request()->extractPost('agency-city', '');
        $streetAgency = App::request()->extractPost('agency-street', '');
        $innAgency = App::request()->extractPost('agency-inn', '');
        $kppAgency = App::request()->extractPost('agency-kpp', '');
        $ogrnAgency = App::request()->extractPost('agency-ogrn', '');
        $rsAgency = App::request()->extractPost('agency-rs', '');
        $bankAgency = App::request()->extractPost('agency-bank', '');
        $ksAgency = App::request()->extractPost('agency-ks', '');
        $bikAgency = App::request()->extractPost('agency-bik', '');
        $legalAddressAgency = App::request()->extractPost('agency-legal-address', '');

        if (!empty($userId)) {
            $inputFields = [
                'password' => !empty($password) ? $password : '',
                'password_retype' => !empty($passwordRetype) ? $passwordRetype : '',
                'first_name' => !empty($firstName) ? $firstName : '',
                'last_name' => !empty($lastName) ? $lastName : '',
                'middle_name' => !empty($middleName) ? $middleName : '',
                'role' => intval($role) ? $role : 5,
                'email' => !empty($email) ? $email : '',
                'phone' => !empty($phone) ? $phone : '',
                'mobilephone' => !empty($mobilePhone) ? $mobilePhone : '',
                'gender' => !empty($gender) ? $gender : '',
                'country' => !empty($country) ? $country : '',
                'street' => !empty($street) ? $street : '',
                'city' => !empty($city) ? $city : '',
                'state' => !empty($state) ? $state : '',
                'fax' => !empty($fax) ? $fax : '',

                'agency' => !empty($agency) ? $agency : '',
                'agency_country' => !empty($countryAgency) ? $countryAgency : '',
                'agency_state' => !empty($regionAgency) ? $regionAgency : '',
                'agency_city' => !empty($cityAgency) ? $cityAgency : '',
                'agency_street' => !empty($streetAgency) ? $streetAgency : '',
                'agency_zip' => !empty($zipAgency) ? $zipAgency : '',
                'agency_inn' => !empty($innAgency) ? $innAgency : '',
                'agency_kpp' => !empty($kppAgency) ? $kppAgency : '',
                'agency_ogrn' => !empty($ogrnAgency) ? $ogrnAgency : '',
                'agency_rs' => !empty($rsAgency) ? $rsAgency : '',
                'agency_bank' => !empty($bankAgency) ? $bankAgency : '',
                'agency_ks' => !empty($ksAgency) ? $ksAgency : '',
                'agency_bik' => !empty($bikAgency) ? $bikAgency : '',
                'agency_legal_address' => !empty($legalAddressAgency) ? $legalAddressAgency : '',
            ];

            if ($userModel->updateProfile($userId, $inputFields)) {
                if (($role === UserRolesModel::ROLE_ID_AGENCY) and (intval($userData['attributes']['role']) !== $role)) {
                    (new MailerController)->actionSendUserAgencyRequest($userId);
                }

                $userModel->loadUserData(intval($userId));

                App::init()->redirect('/{lk}/profile');
            } else {
                App::init()->redirect('/{lk}');
            }
        }
    }
}
