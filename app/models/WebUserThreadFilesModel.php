<?php

namespace mmaurice\cabinet\models;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\helpers\FileHelper;
use mmaurice\cabinet\models\Model;

class WebUserThreadFilesModel extends Model
{
    const UNDELETED = 0;
    const DELETED = 1;

    public $tableName = 'web_user_thread_files';

    public function getList($filter = [], $getRelatedItems = false, $log = false)
    {
        $results = parent::getList($filter, $getRelatedItems, $log);

        if (is_array($results) and !empty($results)) {
            $results = array_map(function($value) {
                return self::prepareItem($value);
            }, $results);
        }

        return $results;
    }

    public function getItem($filter = [], $getRelatedItems = false, $log = false)
    {
        $result = parent::getItem($filter, $getRelatedItems, $log);

        if (is_array($result) and !empty($result)) {
            $result = self::prepareItem($result);
        }

        return $result;
    }

    static public function prepareItem(array $row)
    {
        $row['ext'] = pathinfo($row['real_file_name'], PATHINFO_EXTENSION);
        $row['name'] = $row['file_name'];
        $row['file_name'] = "{$row['file_name']}.{$row['ext']}";
        $row['real_file_path'] = App::init()->makeUrl('/{lk}/api/user/messages/download', [
            'fileId' => $row['id'],
        ]);

        return $row;
    }

    public function addMessageFiles($messageId, array $files = [])
    {
        if (is_array($files) and !empty($files)) {
            foreach ($files as $file) {
                $this->addMessageFile($messageId, $file['name'], $file['file'], $file['type'], $file['size']);
            }

            return true;
        }

        return false;
    }

    public function addMessageFile($messageId, $name, $file, $mime = null, $size = null)
    {
        // Добавить метод маскировки вывода (для избежания прямого скачивания)
        // При редактировании сообщения нужно вывести массив загруженных файлов в дропзону
        //      https://github.com/dropzone/dropzone/discussions/1909
        //      https://docs.dropzone.dev/configuration/basics/configuration-options
        // Корректно удалять загруженные файлы
        // Вывести файлы и их загрузку в виджет в ЛК

        $from = realpath($_SERVER['DOCUMENT_ROOT'] . "/" . $file);
        $to = realpath(dirname(__FILE__) . "/..") . "/media/user_files/messages/{$messageId}/" . pathinfo($file, PATHINFO_BASENAME);

        if (!realpath($to)) {
            FileHelper::moveFile($from, $to);
        }

        if (realpath($to)) {
            if ($info = FileHelper::fileInfo($to)) {
                $info['name'] = $name;

                $fields = [
                    'message_id' => $messageId,
                    'file_name' => $name,
                    'real_file_name' => $info['file'],
                    'mime' => $info['type'],
                    'size' => $info['size'],
                ];

                return $this->insert($fields);
            }
        }

        return false;
    }

    public function removeMessageFile($messageId, $file)
    {
        //return $this->delete("`message_id` = '{$messageId}' AND `real_file_name` = '{$file}'");
    }

    public function removeMessageFileId($fileId)
    {
        //return $this->delete("`id` = '{$fileId}'");
        return $this->update([
            'deleted' => self::DELETED,
        ], "`id` = '{$fileId}'");
    }

    public function updateMessageFiles($messageId, array $files = [])
    {
        $existsFiles = $this->getMessageFiles($messageId);
        $addFiles = [];
        $removeFiles = [];

        if (is_array($existsFiles) and !empty($existsFiles)) {
            if (is_array($files) and !empty($files)) {
                $existsFilesTmp = array_map(function($value) {
                    return ltrim($value['real_file_name'], '/');
                }, $existsFiles);

                $filesTmp = array_map(function($value) {
                    return ltrim($value['file'], '/');
                }, $files);

                foreach ($existsFilesTmp as $key => $existsFileTmp) {
                    if (!in_array($existsFileTmp, $filesTmp)) {
                        $removeFiles[] = $existsFiles[$key];
                    }
                }

                foreach ($filesTmp as $key => $fileTmp) {
                    if (!in_array($fileTmp, $existsFilesTmp)) {
                        $addFiles[] = $files[$key];
                    }
                }
            } else {
                $removeFiles = $existsFiles;
            }
        } else {
            $addFiles = $files;
        }

        if (is_array($addFiles) and !empty($addFiles)) {
            foreach ($addFiles as $addFile) {
                $this->addMessageFile($messageId, $addFile['name'], $addFile['file'], $addFile['type'], $addFile['size']);
            }
        }

        if (is_array($removeFiles) and !empty($removeFiles)) {
            foreach ($removeFiles as $removeFile) {
                $this->removeMessageFileId($removeFile['id']);
            }
        }

        return true;
    }

    public function getMessageFiles($messageId)
    {
        return $this->getList([
            'where' => [
                "`message_id` = '{$messageId}'",
                "AND `deleted` = '" . self::UNDELETED . "'",
            ],
        ]);
    }
}
