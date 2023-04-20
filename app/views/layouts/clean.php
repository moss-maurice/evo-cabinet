<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\core\providers\ModxProvider; ?>

<?php ModxProvider::modxInit(); ?>
<?php $modx = ModxProvider::getModx(); ?>

<div class="cab__container container">
    <div class="row">
        <?= $content ?>
    </div>
</div>

<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/cabinet-extends.css?v=" . time()); ?>
<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/styles.css?v=" . time()); ?>
<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/libs/bootstrap-4.0.0/css/bootstrap.min.css?v=" . time()); ?>

<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/popper.js-1.14.1/js/popper.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/bootstrap-4.0.0/js/bootstrap.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/js/jquery-noconflict.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/js/scripts.js?v=" . time()); ?>