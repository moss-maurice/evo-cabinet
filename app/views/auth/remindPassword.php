<?php use mmaurice\cabinet\core\App; ?>

<div class="row justify-content-center">
    <div class="col-4 p-5 m-5">
        <form action="<?= App::init()->makeUrl('/{lk}/api/auth/remind') ?>" method="post" id="remind">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="d-none" for="email">Введите ваш электронный адрес для сброса пароля</label>
                        <input type="email" name="email" class="form-control px-3 py-4 text-center" id="email"
                            placeholder="Введите ваш электронный адрес для сброса пароля" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="submit" name="submit" class="form__submit btn btn-primary bs col-12"
                            value="Сбросить пароль" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="border-top mt-3 text-center">
                        <div class="px-4 py-1 bg-white col-1 d-inline" style="position: relative; top: -13px;">
                            ИЛИ</div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <a class="btn btn-success bs col-12"
                            href="<?= App::init()->makeUrl('/{lk}/login') ?>">Авторизоваться</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>