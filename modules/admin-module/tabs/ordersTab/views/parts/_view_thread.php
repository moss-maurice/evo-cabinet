<?php

use mmaurice\cabinet\widgets\ThreadLineWidget;

?>

<?= ThreadLineWidget::init([
    'orderId' => $orderId,
    'template' => 'module',
])->run(); ?>

<div class="split"></div>
