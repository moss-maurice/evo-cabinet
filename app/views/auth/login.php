<?php use mmaurice\cabinet\core\App; ?>

<div class="row justify-content-center">
    <div class="col-4 p-5 m-5">
        <form action="<?= App::init()->makeUrl('/{lk}/api/auth/login') ?>" method="post" id="sign-in">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="d-none" for="email">Электронный адрес</label>
                        <input type="email" name="email" class="form-control px-3 py-4 text-center" id="email"
                            placeholder="Электронный адрес" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="d-none" for="password">Пароль</label>
                        <input type="password" name="password" class="form-control px-3 py-4 text-center" id=" password"
                            placeholder="Пароль" />
                    </div>
                </div>
            </div>
            <div class="row py-3">
                <div class="col-sm-6 text-center pt-2">
                    <div class="form-group form-check">
                        <input type="checkbox" name="rememberMe" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Запомнить меня</label>
                    </div>
                </div>
                <div class="col-sm-6 text-center">
                    <div class="form-group p-0 m-0">
                        <a class="bs btn btn-link" href="<?= App::init()->makeUrl('/{lk}/login/remind') ?>">Забыли
                            пароль?</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="submit" name="submit" class="form__submit btn btn-primary bs col-12"
                            value="Войти" />
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
                            href="<?= App::init()->makeUrl('/{lk}/register') ?>">Зарегистрироваться</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>