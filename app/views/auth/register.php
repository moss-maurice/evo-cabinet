<?php use mmaurice\cabinet\core\App; ?>
<div class="row justify-content-center">
    <div class="col-4 p-5 m-5">
        <form action="<?= App::init()->makeUrl('/{lk}/api/auth/register') ?>" method="post" id="sign-up">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="d-none" for="firstName">Ваше имя</label>
                        <input type="text" name="firstName" class="form-control px-3 py-4 text-center" id="firstName"
                            placeholder="Ваше имя" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="d-none" for="email">Электронный адрес</label>
                        <input type="email" name="email" class="form-control px-3 py-4 text-center" id="email"
                            placeholder="Электронный адрес" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="d-none" for="password">Ваш пароль</label>
                        <input type="password" name="password" class="form-control px-3 py-4 text-center" id="password"
                            placeholder="Ваш пароль" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="d-none" for="passwordRetype">Ваш пароль ещё раз</label>
                        <input type="password" name="passwordRetype" class="form-control px-3 py-4 text-center"
                            id="passwordRetype" placeholder="Ваш пароль ещё раз" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="submit" name="submit" class="form__submit btn btn-primary bs col-12"
                            value="Зарегистрироваться" />
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