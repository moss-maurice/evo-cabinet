<?php

use mmaurice\cabinet\models\OrdersStatusesModel;

?>

        <tfoot>
            <tr class="paginator">
                <td class="tableHeader" colspan="<?= (!in_array(intval($orderItem['status']['id']), [OrdersStatusesModel::STATUS_ARCHIVE, OrdersStatusesModel::STATUS_DELETED]) ? 8 : 6); ?>">
                    <?php include realpath(dirname(__FILE__) . '/_list_pagination.php'); ?>
                </td>
            </tr>
        </tfoot>
