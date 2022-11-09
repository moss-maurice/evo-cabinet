<?php use mmaurice\cabinet\models\UserRolesModel; ?>
<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\models\WebUsersModel; ?>
<?php $user = WebUsersModel::model(); ?>
<?php $type = $user->getAttribute('type', UserRolesModel::ROLE_ID_USER); ?>

<h1 class="cab-reg-heading" align="center">Профиль</h1>
<hr>
<form class="form-signin" method="POST" action="<?= App::init()->makeUrl('/{lk}/profile/update') ?>">
    <input type="hidden" name="user-id" value="<?= $user->getId(); ?>">
    <input type="hidden" name="user-type" value="5">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6 form-group"><br>
                            <label>Пароль <span class="req"></span></label>
                            <input type="password" name="password" id="password" placeholder="" value="" minlength="6" />
                        </div>
                        <div class="col-sm-6 form-group"><br>
                            <label>Повтор пароля <span class="req"></span></label>
                            <input type="password" name="password-retype" id="password-retype" placeholder="" value="" minlength="6" />
                        </div>
                    </div><br>

                    <hr><br>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Фамилия <span class="req"></span></label>
                                <input class="form-control" type="text" name="lastName" id="lastName" placeholder="" autofocus="" value="<?= $user->getSetting('last_name', '') ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Имя <span class="req"></span></label>
                                <input class="form-control" type="text" name="firstName" id="firstName" placeholder="" autofocus="" value="<?= $user->getSetting('first_name', '') ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Отчество</label>
                                <input class="form-control" type="text" name="middleName" id="middleName" placeholder="" autofocus="" value="<?= $user->getSetting('middle_name', '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Номер основного телефона</label>
                                <input class="form-control phonemask" type="text" name="phone" id="phone" placeholder="" value="<?= $user->getAttribute('phone') ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Номер мобильного телефона</label>
                                <input class="form-control phonemask" type="text" name="phone-mobile" id="phoneMobile" placeholder="" value="<?= $user->getAttribute('mobilephone') ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>E-mail <span class="req"></span></label>
                                <input class="form-control" type="text" name="email" id="email" placeholder="" value="<?= $user->getAttribute('email', '') ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Факс</label>
                                <input class="form-control" type="text" name="fax" id="fax" placeholder="" value="<?= $user->getAttribute('fax') ?>">
                            </div>
                        </div>
                    </div>

                    <hr><br>

                    <div class="cab-reg-input form-group">
                        <label>Гражданство</label>
                        <select name="country" id="country" placeholder="">
                            <?php if (is_array($countries) and !empty($countries)) : ?>
                                <?php foreach ($countries as $country) : ?>
                                    <option <?= ((intval($country['id']) === (intval($user->getAttribute('country', '')) !== 0 ? intval($user->getAttribute('country', '')) : 150)) ? 'selected' : '') ?> value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div><br>

                    <div id="noneAgencyRegFields">
                        <div class="cab-reg-input form-group">
                            <label>Регион / провинция / область / район</label>
                            <input type="text" name="region" id="region" placeholder="" value="<?= $user->getAttribute('state', '') ?>" />
                        </div>

                        <div class="cab-reg-input form-group">
                            <label>Город</label>
                            <input type="text" name="city" id="city" placeholder="" value="<?= $user->getAttribute('city', '') ?>" />
                        </div>

                        <div class="cab-reg-input form-group">
                            <label>Улица</label>
                            <input type="text" name="street" id="street" placeholder="" value="<?= $user->getAttribute('street', '') ?>" />
                        </div>
                    </div><br>

                    <div class="cab-reg-input form-group">
                        <label>Пол</label>
                        <select name="sex" id="sex" placeholder="Пол">
                            <option value="0">Не определен</option>
                            <option value="1" <?= intval($user->getAttribute('gender', '')) ? 'selected' : '' ?>>Мужской</option>
                            <option value="2" <?= !intval($user->getAttribute('gender', '')) ? 'selected' : '' ?>>Женский</option>
                        </select>
                    </div><br>

                    <div class="row" align="center">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="submit" value="Сохранить">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        let agencyContainer = jQuery(document).find("#agencyRegFields");

        jQuery(document).find("input[name=user-type-radio]").on('change', function() {
            if (parseInt(jQuery(this).val()) == 5) {
                jQuery(agencyContainer).addClass('d-none');

                jQuery(document).find('input[name=user-type]').val(5);
            } else if (parseInt(jQuery(this).val()) == 6) {
                jQuery(agencyContainer).removeClass('d-none');

                jQuery(document).find('input[name=user-type]').val(6);
            }
        });

        toggleAgencyRegFields();
    });
</script>