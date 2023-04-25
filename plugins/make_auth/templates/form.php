<?php use mmaurice\cabinet\core\App; ?>

<div class="text-right m-0 p-0">
    <a href="<?= App::init()->makeUrl('/{lk}') ?>" class="btn btn-link btn-sm bs p-0 m-0 font-weight-bold">Личный
        кабинет</a>
    <span class="px-1 text-primary">|</span>
    <a href="<?= App::init()->makeUrl('/{lk}/logout') ?>"
        class="btn btn-link btn-sm bs p-0 m-0 font-weight-bold">Выйти</a>
</div>

<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/libs/bootstrap-4.0.0/css/bootstrap.min.css?v=" . time()); ?>