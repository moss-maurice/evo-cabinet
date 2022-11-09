<?php

use mmaurice\cabinet\core\App;

?>

<link rel="stylesheet" type="text/css" href="/template/css/bootstrap-grid.min.css">
<link rel="stylesheet" type="text/css" href="/template/css/bootstrap-spacing.min.css">
<link rel="stylesheet" type="text/css" href="<?= App::getPublicWebRoot('/assets/libs/fontawesome-5.15.4/css/all.min.css'); ?>?v=<?= time(); ?>" />

<?php include realpath(dirname(__FILE__) . '/parts/_list_filter.php'); ?>

<div class="table-responsive">
<?php if (is_array($usersList) and !empty($usersList)) : ?>
    <table id="usersList" class="table data" cellpadding="1" cellspacing="1">
        <?php include realpath(dirname(__FILE__) . '/parts/_list_table_header.php'); ?>
        <?php include realpath(dirname(__FILE__) . '/parts/_list_table_body.php'); ?>
        <?php include realpath(dirname(__FILE__) . '/parts/_list_table_footer.php'); ?>
    </table>
<?php else : ?>
    <?php include realpath(dirname(__FILE__) . '/parts/_list_no_found.php'); ?>
<?php endif; ?>
</div>

<script type="text/javascript" src="<?= App::getPublicWebRoot('/modules/admin-module/assets/js/customs/usersTab/list/script.js'); ?>?v=<?= time(); ?>"></script>
