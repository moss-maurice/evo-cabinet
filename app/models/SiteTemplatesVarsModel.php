<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\models\SiteTmplvarsModel as ParentSiteTmplvarsModel;

class SiteTmplvarsModel extends ParentSiteTmplvarsModel
{
    /**
     * Extract template vars by tourId and varTitle.
     *
     * @param int $tourId
     * @param string $title
     * @return void
     */
    public function getTemplateVarByTourIdAndTitle($tourId, $title)
    {
        $result = $this->getItem([
            'select' => [
                "`estc_tour`.`value` as `value`"
            ],
            'from' => "`evo_site_content` ",
            "join" => [
                "LEFT JOIN `evo_site_tmplvars` as `est_tour` ON `est_tour`.`name` = '{$title}'
                 LEFT JOIN `evo_site_tmplvar_contentvalues` as `estc_tour` ON `estc_tour`.`tmplvarid` = `est_tour`.`id`
                    AND `estc_tour`.`contentid` = `t`.`id`"
            ],
            'where' => [
                "`t`.`id` = '{$tourId}';"
            ]
        ]);

        return $result;
    }
}