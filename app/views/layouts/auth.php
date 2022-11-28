<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\core\providers\ModxProvider; ?>

<?php ModxProvider::modxInit(); ?>
<?php $modx = ModxProvider::getModx(); ?>

<!-- LK Snippet [ -->
<?= $content ?>

<?php //$modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/jquery-3.3.1/js/jquery-3.3.1.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/formstyler-2.0.1/js/jquery.formstyler.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/popper.js-1.14.1/js/popper.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/bootstrap-4.0.0/js/bootstrap.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/js/cabinet-auth.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/js/scripts.js?v=" . time()); ?>

<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/styles.min.css?v=" . time()); ?>
<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/cabinet.css?v=" . time()); ?>
<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/cabinet-extends.css?v=" . time()); ?>
<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/styles.css?v=" . time()); ?>
<!-- ] LK Snippet -->
