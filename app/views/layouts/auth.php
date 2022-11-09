<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\core\providers\ModxProvider; ?>

<?php ModxProvider::modxInit(); ?>
<?php $modx = ModxProvider::getModx(); ?>

<!-- LK Snippet [ -->
<?= $content ?>

<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/formstyler-2.0.1/js/jquery.formstyler.min.js"); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/popper.js-1.14.1/js/popper.min.js"); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/bootstrap-4.0.0/js/bootstrap.min.js"); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/vue/vue.min.js"); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/vue/axios.min.js"); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/js/cabinet.js"); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/js/auth.js"); ?>

<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/cabinet.css"); ?>
<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/cabinet-extends.css"); ?>
<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/styles.css"); ?>
<!-- ] LK Snippet -->
