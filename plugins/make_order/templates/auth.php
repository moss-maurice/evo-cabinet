<?php use mmaurice\cabinet\core\App; ?>

<div class="alert alert-warning text-center" role="alert">
    <div class="py-3 font-weight-bold">Оформить заявку могут только авторизированные пользователи.<br />Перед началом, войдите в личный кабинет!</div>
    <div>
        <a class="btn bs btn-light" href="<?= App::init()->makeUrl('/{lk}/login') ?>">Авторизация</a>
        <a class="btn bs btn-light" href="<?= App::init()->makeUrl('/{lk}/register') ?>">Регистрация</a>
    </div>
</div>

<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/libs/bootstrap-4.0.0/css/bootstrap.min.css?v=" . time()); ?>