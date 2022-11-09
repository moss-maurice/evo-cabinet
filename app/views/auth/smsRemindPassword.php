<?php

use mmaurice\cabinet\core\App; ?>

<h1 class="cab-reg-heading">Регистрация</h1>

<hr>

<div>
    <p>Здесь Вы можете зарегистрироваться как турист или туристическое агентство. После регистрации Вам будет доступен личный кабинет.</p>
    <p>Если Вы турагентство, то дополнительные функции станут доступны после подтверждения Вашего статуса администраторами.</p>
</div>

<hr>

<form class="form-signin" method="POST">
    <div class="row">
        <div class="col-sm-12">

            <div class="row">
                <div class="col-sm-12">
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Номер мобильного телефона <small>(login)</small></label>
                                <input class="form-control" type="text" name="mobile-phone" id="mobile-phone" placeholder="" value="+71234567891">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="cab-reg-button">
                            <div class="message badge badge-danger"><?= $message; ?></div>
                            <button class="b-button b-button_full-width" class="submit">Отправить</button>
                        </div>

                        <div class="cab-reg-already">Уже зарегистрировались? <a href="<?= App::init()->makeUrl('/{lk}/login/'); ?>">Войти</a></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
</div>