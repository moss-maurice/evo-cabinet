<?php

use mmaurice\cabinet\core\App;

?>

<?php include realpath(dirname(__FILE__) . '/parts/_list_filter.php'); ?>

<div class="table-responsive">
<?php if (is_array($ordersList) and !empty($ordersList)) : ?>
    <table id="ordersList" class="table data">
        <?php include realpath(dirname(__FILE__) . '/parts/_list_table_header.php'); ?>
        <?php include realpath(dirname(__FILE__) . '/parts/_list_table_body.php'); ?>
        <?php include realpath(dirname(__FILE__) . '/parts/_list_table_footer.php'); ?>
    </table>
<?php else : ?>
    <?php include realpath(dirname(__FILE__) . '/parts/_list_no_found.php'); ?>
<?php endif; ?>
</div>

<script type="text/javascript" src="<?= $modulePath; ?>/assets/js/customs/ordersTab/list/script.js?v=<?= time(); ?>"></script>
