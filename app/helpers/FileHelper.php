<?php

namespace mmaurice\cabinet\helpers;

class FileHelper
{
    static public function moveFile($from, &$to)
    {
        $toDirname = pathinfo($to, PATHINFO_DIRNAME);
        $toBasepath = pathinfo($to, PATHINFO_BASENAME);

        if (!realpath($toDirname)) {
            mkdir($toDirname, 0777, true);
        }

        if (realpath($toDirname)) {
            $toDirname = realpath($toDirname);

            $move = move_uploaded_file($from, "{$toDirname}/{$toBasepath}");

            if (!$move) {
                $move = rename($from, "{$toDirname}/{$toBasepath}");
            }

            if ($move and ($to = realpath("{$toDirname}/{$toBasepath}"))) {
                return true;
            }
        }

        return false;
    }

    static public function fileInfo($file)
    {
        if (realpath($file)) {
            return [
                'file' => self::pathAlias(realpath($file)),
                'name' => pathinfo($file, PATHINFO_FILENAME),
                'type' => mime_content_type($file),
                'size' => filesize($file),
            ];
        }

        return null;
    }

    static public function pathAlias($path)
    {
        $path = str_replace(["\\", "/"], '/', $path);
        $path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);

        return ltrim($path, '/');
    }

    static public function downloadFile($file, $name = null)
    {
        if (file_exists($file)) {
            if (ob_get_level()) {
                ob_end_clean();
            }

            if (is_null($name)) {
                $name = basename($file);
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $name);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));

            readfile($file);

            die();
        }

        return false;
    }
}
