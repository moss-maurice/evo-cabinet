<?php

namespace mmaurice\cabinet\helpers;

class ImagesHelper
{
    /**
     * Метод генерации структуры каталогов для файла изображения
     *
     * @param string $fileName
     * @return void
     */
    static public function getImageStructure($fileName)
    {
        $result = '';

        if (!empty($fileName) and !is_null($fileName)) {
            preg_match('/^(.{2})(.{2}).*$/i', $fileName, $matches);

            if (!empty($matches)) {
                array_shift($matches);

                $result = '/' . $matches[0] . '/' . $matches[1];
            }
        }

        return $result;
    }

    static public function getTourImages($tourId)
    {
        global $modx;

        $sql = "SELECT
                *
            FROM evo_sg_images
            WHERE
                sg_rid = '{$tourId}'
            ORDER BY
                sg_index DESC
            LIMIT 1;";

        $resource = $modx->db->query($sql);

        $images = '';

        if ($modx->db->getRecordCount($resource)) {
            while ($image = $modx->db->getRow($resource)) {
                $images = $modx->runSnippet('phpthumb', array(
                    'input' => $image['sg_image'],
                    'options' => 'w=138,h=100,q=90,zc=1',
                ));

                break;
            }
        }

        return $images;
    }
}
