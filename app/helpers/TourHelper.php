<?php

namespace mmaurice\cabinet\helpers;

use mmaurice\cabinet\models\SiteContentExcursionsModel;
use mmaurice\cabinet\models\SiteContentToursModel;
use mmaurice\cabinet\models\OrdersModel;

class TourHelper
{
    static public function getTourFromOrder($orderId)
    {
        $model = new OrdersModel();

        $order = $model->getItem(array(
            'where' => [
                "t.id = '{$orderId}'"
            ],
        ), true);

        if (array_key_exists('arrive', $order) and !is_null($order['arrive'])) {
            if (array_key_exists('tour', $order['arrive']) and !is_null($order['arrive']['tour'])) {
                return $order['arrive']['tour'];
            } else if (array_key_exists('excursion', $order['arrive']) and !is_null($order['arrive']['excursion'])) {
                return $order['arrive']['excursion'];
            }
        } else if (array_key_exists('voyage', $order) and !is_null($order['voyage'])) {
            if (array_key_exists('tour', $order['voyage']) and !is_null($order['voyage']['tour'])) {
                return $order['voyage']['tour'];
            } else if (array_key_exists('excursion', $order['voyage']) and !is_null($order['voyage']['excursion'])) {
                return $order['voyage']['excursion'];
            }
        } else if (array_key_exists('tour', $order) and !is_null($order['tour'])) {
            return $order['tour'];
        }

        return null;
    }

    static public function getTourType($tourId)
    {
        $model = new SiteContentToursModel();

        $tour = $model->getItem(array(
            'where' => [
                "t.id = '{$tourId}'"
            ],
        ));

        if ($tour) {
            switch (intval($tour['template'])) {
                case SiteContentToursModel::TEMPLATE_ID:
                    return SiteContentToursModel::TEMPLATE_NAME;
                case SiteContentExcursionsModel::TEMPLATE_ID:
                    return SiteContentExcursionsModel::TEMPLATE_NAME;
            }
        }

        return null;
    }

    static public function isTour($tourId)
    {
        $checkResult = self::getTourType(intval($tourId));

        if ($checkResult === SiteContentToursModel::TEMPLATE_NAME) {
            return true;
        }

        return false;
    }

    static public function isExcursion($tourId)
    {
        $checkResult = self::getTourType(intval($tourId));

        if ($checkResult === SiteContentExcursionsModel::TEMPLATE_NAME) {
            return true;
        }

        return false;
    }

    static public function getVoyagesByDirections($voyages)
    {
        if (
            array_key_exists('out', $voyages) and !is_null($voyages['out']) and
            array_key_exists('in', $voyages) and !is_null($voyages['in'])
        ) {
            foreach ($voyages['out'] as $keyOut => $voyageOut) {
                foreach ($voyages['in'] as $keyIn => $voyageIn) {
                    if ($voyageOut['tourVoyageId'] == $voyageIn['tourVoyageId']) {
                        $voyages['both'][] = [
                            'out' => $voyageOut,
                            'in' => $voyageIn
                        ];

                        unset($voyages['out'][$keyOut]);
                        unset($voyages['in'][$keyIn]);
                    }
                }
            }

            if (empty($voyages['in'])) {
                unset($voyages['in']);
            }

            if (empty($voyages['out'])) {
                unset($voyages['out']);
            }
        }

        return $voyages;
    }
}
