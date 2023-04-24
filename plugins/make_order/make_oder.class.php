<?php

class MakeOrderPlugin
{
    public static function getParams()
    {
        global $modx;

        switch ($modx->event->name) {
            case 'OnParseDocument':
                return static::chunkRunned() ? static::extractChunkParamas() : [];
            default:
                return [];

                break;
        }

        return [];
    }

    protected static function chunkRunned()
    {
        return static::extractChunk() ? true : false;
    }

    protected static function extractChunk()
    {
        global $modx;

        $content = &$modx->documentOutput;

        if (preg_match('/(?:\{\{)cabinetOrder(?:\}\}|\s*\?\s*([^\}]+)\}\})/imu', $content, $matches)) {
            return $matches;
        }

        return null;
    }

    protected static function extractChunkParamas()
    {
        $params = [];

        if (static::chunkRunned()) {
            $chunk = static::extractChunk();

            if (preg_match_all('/\&[.]*([^\=\s]+)\=\`([^\`]*)\`/i', $chunk[1], $matches)) {
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $params[str_replace('amp;', '', $matches[1][$i])] = $matches[2][$i];
                }
            }
        }

        return $params;
    }

    public static function drawAuth()
    {
        return static::drawTemplare('auth');
    }

    public static function drawForm()
    {
        return static::drawTemplare('form', static::getParams());
    }

    protected static function drawTemplare($template, $params = [])
    {
        global $modx;

        if (static::chunkRunned()) {
            $chunk = static::extractChunk();

            $content = &$modx->documentOutput;

            $render = static::makeTemplate($template, [
                'params' => $params,
            ]);

            $content = str_replace($chunk[0], $render, $content);

            return true;
        }

        return false;
    }

    protected static function makeTemplate($__tplName__, $__variables__ = [])
    {
        global $modx;

        $__tplName__ = trim($__tplName__);
        $__tplPath__ = static::getTemplateFullPath($__tplName__);

        if (!file_exists($__tplPath__) or !is_file($__tplPath__)) {
            die("Template file \"{$__tplName__}\" is not found!");
        }

        $__variables__ = array_merge($__variables__, [
            'modx' => $modx,
        ]);

        extract($__variables__, EXTR_PREFIX_SAME, 'data');

        ob_start();

        ob_implicit_flush(false);
        include($__tplPath__);

        $content = ob_get_clean();

        return $content;
    }

    protected static function getTemplateFullPath($tplName)
    {
        return realpath(dirname(__FILE__) . '/templates/' . $tplName . '.php');
    }
}