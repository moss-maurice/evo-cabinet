<?php use mmaurice\cabinet\core\App; ?>
<?php use mmaurice\cabinet\core\providers\ModxProvider; ?>
<?php use mmaurice\cabinet\widgets\LeftMenuWidget; ?>
<?php use mmaurice\cabinet\widgets\ToolbarWidget; ?>

<?php ModxProvider::modxInit(); ?>
<?php $modx = ModxProvider::getModx(); ?>

<!-- LK Snippet [ -->
<div class="cab__container wr">
    <div class="row">
        <!-- Left column [ -->
        <div class="col-sm-12 cab-aside">
            <?= LeftMenuWidget::init([
        'menu' => [
            [
                'title' => 'Заявки',
                'link' => '/{lk}/orders',
                'class' => 'icon-zayavki',
                'role' => 'all',
            ],
            [
                'title' => 'Профиль',
                'link' => '/{lk}/profile',
                'class' => 'icon-profile',
                'role' => 'all',
            ],
        ],
    ])->run(); ?>
        </div>
    </div>
    <div class="row">
        <!-- ] Left column -->
        <!-- Center column [ -->
        <div class="col-sm-12 cab-content">
            <?= ToolbarWidget::init([
        'menu' => [
            [
                'title' => 'Заявки',
                'link' => '/{lk}/orders',
                'class' => 'icon-zayavki',
                'role' => 'all',
            ],
            [
                'title' => 'Профиль',
                'link' => '/{lk}/profile',
                'class' => 'icon-profile',
                'role' => 'all',
            ],
        ],
    ])->run(); ?>
            <?= $content ?>
        </div>
        <!-- ] Center column -->
    </div>
</div>

<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/cabinet-extends.css?v=" . time()); ?>
<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/css/styles.css?v=" . time()); ?>
<?php $modx->regClientCSS(App::getPublicWebRoot() . "/assets/libs/bootstrap-4.0.0/css/bootstrap.min.css?v=" . time()); ?>

<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/popper.js-1.14.1/js/popper.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/libs/bootstrap-4.0.0/js/bootstrap.min.js?v=" . time()); ?>
<?php $modx->regClientScript(App::getPublicWebRoot() . "/assets/js/scripts.js?v=" . time()); ?>

<?php $modx->regClientScript("<script>var paymentType = " . ($modx->getConfig('client_equiringEnable') ? $modx->getConfig('client_equiringEnable') : 0) . ";</script>"); ?>
<!-- ] LK Snippet -->