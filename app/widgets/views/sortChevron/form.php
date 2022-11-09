<?php
use mmaurice\cabinet\widgets\SortChevronWidget;
use mmaurice\cabinet\core\App;
?>

<link rel="stylesheet" type="text/css" href="<?= App::getPublicWebRoot('/widgets/views/sortChevron/assets/styles/style.css'); ?>?v=<?= time(); ?>">
<script src="<?= App::getPublicWebRoot('/widgets/views/sortChevron/assets/scripts/script.js'); ?>?v=<?= time(); ?>" type="text/javascript"></script>

<small class="order order-control<?= ($active ? ' active' : ''); ?>" rel-tab="<?= $tab; ?>" rel-tab-method="<?= $method; ?>" rel-page="<?= $page; ?>" rel-sort-field="<?= $field; ?>" rel-sort-direction="<?= $direction; ?>">
<?php if (($currentField === $field) and ($currentDirection === SortChevronWidget::DIRECTION_DESC)) : ?>
    <i class="fas fa-chevron-up"></i>
<?php else: ?>
    <i class="fas fa-chevron-down"></i>
<?php endif; ?>
</small>
