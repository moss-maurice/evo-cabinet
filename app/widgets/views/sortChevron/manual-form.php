<?php
use mmaurice\cabinet\widgets\SortChevronWidget;
use mmaurice\cabinet\core\App;
?>

<link rel="stylesheet" type="text/css" href="<?= App::getPublicWebRoot('/widgets/views/sortChevron/assets/styles/style.css'); ?>?v=<?= time(); ?>">
<script src="<?= App::getPublicWebRoot('/widgets/views/sortChevron/assets/scripts/script.js'); ?>?v=<?= time(); ?>" type="text/javascript"></script>

<small class="order order-control" rel-tab="<?= $tab; ?>" rel-tab-method="<?= $method; ?>" rel-page="<?= $page; ?>" rel-sort-field="<?= $field; ?>" rel-sort-direction="<?= $direction; ?>"><i class="fas fa-chevron-<?= (($direction === SortChevronWidget::DIRECTION_ASC) ? 'down' : 'up'); ?>"></i></small>
