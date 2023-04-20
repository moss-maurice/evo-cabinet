<?php use mmaurice\cabinet\models\UserRolesModel; ?>
<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\models\WebUsersModel; ?>
<?php use mmaurice\cabinet\widgets\PageTitleWidget; ?>

<?php $user = WebUsersModel::model(); ?>
<?php $type = $user->getAttribute('type', UserRolesModel::ROLE_ID_USER); ?>

<?= PageTitleWidget::init([
    'title' => 'Профиль',
])->run(); ?>

<form method="POST" action="<?= App::init()->makeUrl('/{lk}/profile/update') ?>">
    <input type="hidden" name="user-id" value="<?= $user->getId(); ?>">
    <input type="hidden" name="user-type" value="5">

    <div class="card mb-5">
        <div class="card-header h5 text-center">Персональные данные</div>
        <div class="card-body mx-3 py-3">
            <div class="row">
                <div class="form-group col-sm-4">
                    <label for="last_name">Фамилия <span class="req"></span></label>
                    <input type="text" name="last_name" class="form-control" id="last_name"
                        value="<?= $user->getSetting('last_name', '') ?>" />
                </div>
                <div class="form-group col-sm-4">
                    <label for="first_name">Имя <span class="req"></span></label>
                    <input type="text" name="first_name" class="form-control" id="first_name"
                        value="<?= $user->getSetting('first_name', '') ?>" />
                </div>
                <div class="form-group col-sm-4">
                    <label for="middle_name">Отчество</label>
                    <input type="text" name="middle_name" class="form-control" id="middle_name"
                        value="<?= $user->getSetting('middle_name', '') ?>" />
                </div>
                <div class="form-group col-sm-6">
                    <label for="email">E-mail <span class="req"></span></label>
                    <input type="text" name="email" class="form-control" id="email"
                        value="<?= $user->getAttribute('email', '') ?>" />
                </div>
                <div class="form-group col-sm-6">
                    <label for="gender">Пол</label>
                    <select name="gender" id="gender" placeholder="Пол">
                        <option value="0">Не определен</option>
                        <option value="1" <?= intval($user->getAttribute('gender', '')) ? 'selected' : '' ?>>Мужской
                        </option>
                        <option value="2" <?= !intval($user->getAttribute('gender', '')) ? 'selected' : '' ?>>Женский
                        </option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="phone">Номер основного телефона</label>
                    <input type="text" name="phone" class="form-control phonemask" id="phone"
                        value="<?= $user->getAttribute('phone', '') ?>" />
                </div>
                <div class="form-group col-sm-4">
                    <label for="mobilephone">Номер мобильного телефона</label>
                    <input type="text" name="mobilephone" class="form-control phonemask" id="mobilephone"
                        value="<?= $user->getAttribute('mobilephone', '') ?>" />
                </div>
                <div class="form-group col-sm-4">
                    <label for="fax">Номер факса</label>
                    <input type="text" name="fax" class="form-control phonemask" id="fax"
                        value="<?= $user->getAttribute('fax', '') ?>" />
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <input type="submit" class="bs btn btn-sm btn-primary" value="Сохранить" />
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header h5 text-center">Адрес</div>
        <div class="card-body mx-3 py-3">
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="country">Страна</label>
                    <select name="country" id="country" placeholder="">
                        <?php if (is_array($countries) and !empty($countries)) : ?>
                        <?php foreach ($countries as $country) : ?>
                        <option
                            <?= ((intval($country['id']) === (intval($user->getAttribute('country', '')) !== 0 ? intval($user->getAttribute('country', '')) : 150)) ? 'selected' : '') ?>
                            value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="zip">Почтовый индекс</label>
                    <input type="text" name="zip" class="form-control" id="zip"
                        value="<?= $user->getAttribute('zip', '') ?>" />
                </div>
                <div class="form-group col-sm-6">
                    <label for="state">Регион / провинция / область / район</label>
                    <input type="text" name="state" class="form-control" id="state"
                        value="<?= $user->getAttribute('state', '') ?>" />
                </div>
                <div class="form-group col-sm-6">
                    <label for="city">Город</label>
                    <input type="text" name="city" class="form-control" id="city"
                        value="<?= $user->getAttribute('city', '') ?>" />
                </div>
                <div class="form-group col-sm-6">
                    <label for="street">Улица</label>
                    <input type="text" name="street" class="form-control" id="street"
                        value="<?= $user->getAttribute('street', '') ?>" />
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <input type="submit" class="bs btn btn-sm btn-primary" value="Сохранить" />
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header h5 text-center">Пароль</div>
        <div class="card-body mx-3 py-3">
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="password">Пароль <span class="req"></span></label>
                    <input type="password" name="password" class="form-control" id="password" value="" />
                </div>
                <div class="form-group col-sm-6">
                    <label for="password-retype">Повтор пароля <span class="req"></span></label>
                    <input type="password" name="password-retype" class="form-control" id="password-retype" value="" />
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <input type="submit" class="bs btn btn-sm btn-primary" value="Сохранить" />
        </div>
    </div>
</form>