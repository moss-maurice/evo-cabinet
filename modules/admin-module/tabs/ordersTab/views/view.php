<?php

use mmaurice\cabinet\helpers\TourHelper;
use mmaurice\cabinet\core\App;

?>

<?php $orderId = intval($order['id']); ?>
<?php $tour = TourHelper::getTourFromOrder($orderId); ?>
<?php $tourId = intval($tour['id']); ?>
<?php $tourName = $tour['pagetitle']; ?>
<?php if ($payments) : ?>
    <?php $order['payments'] = $payments; ?>
<?php endif; ?>
<?php $relatedPlaces = []; ?>
<?php $placesTo = [] ?>
<?php $placesFrom = [] ?>
<?php if (is_array($order['places']) and !empty($order['places'])) : ?>
    <?php foreach ($order['places'] as $placesItem) : ?>
        <?php if (!array_key_exists(intval($placesItem['place']), $relatedPlaces)) : ?>
            <?php $relatedPlaces[intval($placesItem['place'])] = $placesItem['place']; ?>
        <?php endif; ?>
        <?php if (intval($placesItem['voyages']['direction_id']) === 1) : ?>
            <?php $placesTo[] = $placesItem; ?>
        <?php else : ?>
            <?php $placesFrom[] = $placesItem; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php $relatedPlaces = array_values($relatedPlaces); ?>
<?php endif; ?>

<?php $payments = 0; ?>
<?php if (is_array($order['payments']) and !empty($order['payments'])) : ?>
    <?php foreach ($order['payments'] as $payment) : ?>
        <?php if (!is_null($payment['payer'])) : ?>
            <?php $payments += $payment['transaction_value']; ?>
        <?php else : ?>
            <?php $adminPayments += $payment['transaction_value']; ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php $payments = number_format($payments, 2, '.', ''); ?>
<?php endif; ?>

<?php include realpath(dirname(__FILE__) . '/parts/_view_controls.php'); ?>
<?php include realpath(dirname(__FILE__) . '/parts/_view_messages.php'); ?>
<?php include realpath(dirname(__FILE__) . '/parts/_view_order_info.php'); ?>
<hr />
<?php include realpath(dirname(__FILE__) . '/parts/_view_payments.php'); ?>
<?php include realpath(dirname(__FILE__) . '/parts/_view_thread.php'); ?>
