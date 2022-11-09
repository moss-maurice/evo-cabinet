<?php

use mmaurice\cabinet\core\App;

?>

<link rel="stylesheet" type="text/css" href="/template/css/bootstrap-grid.min.css">
<link rel="stylesheet" type="text/css" href="/template/css/bootstrap-spacing.min.css">
<link rel="stylesheet" type="text/css" href="<?= App::getPublicWebRoot('/assets/libs/fontawesome-5.15.4/css/all.min.css'); ?>?v=<?= time(); ?>" />

<?php include realpath(dirname(__FILE__) . '/parts/_view_controls.php'); ?>

<div id="user-item" rel-item-id="<?= $itemId; ?>" rel-tab="<?= $tabName; ?>" rel-method="update">
    <?php include realpath(dirname(__FILE__) . '/parts/_view_personal.php'); ?>
    <?php include realpath(dirname(__FILE__) . '/parts/_view_secure.php'); ?>
</div>

<script type="text/javascript" src="/admin/media/calendar/datepicker.js"></script>
