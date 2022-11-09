<?php use mmaurice\cabinet\widgets\AdminPaginatorWidget; ?>

<?= AdminPaginatorWidget::init([
    'container' => '<div class="lk-module-pagination pagination d-flex justify-content-center align-items-baseline" rel-tab="' . $tabName . '" rel-method="' . $tabMethod . '" rel-item-id="' . $dataId . '">[content]<span class="btn" rel-page="0">Показать всё</span></div>',
])->run($page, $pages); ?>
