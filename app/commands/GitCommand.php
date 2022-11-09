<?php

namespace mmaurice\cabinet\commands;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\helpers\CmdHelper;
use mmaurice\cabinet\core\prototypes\commands\CommandProtorype;
use mmaurice\cabinet\core\providers\ModxProvider;

class GitCommand extends CommandProtorype
{
    // php cli.php git/pull
    public function actionPull()
    {
        global $database_server;
        global $database_user;
        global $database_password;
        global $dbase;
        global $table_prefix;
        global $modx;

        ModxProvider::modxInit();
        $modx = ModxProvider::getModx();

        $webRoot = realpath($_SERVER['DOCUMENT_ROOT']);

        //shell_exec("cd {$webRoot} && git reset --hard HEAD");
        shell_exec("cd {$webRoot} && git stash && git stash drop && git reset --hard HEAD");

        $response = shell_exec("cd {$webRoot} && git pull");

        CmdHelper::logLine(CmdHelper::textColor('white', $response));

        //$response = shell_exec("cd {$webRoot} && composer-php5.6 update --no-interaction");

        //CmdHelper::logLine(CmdHelper::textColor('white', $response));

        if (trim($response) !== 'Already up to date.') {
            $modx->clearCache();
            $modx->clearCache('full');

            CmdHelper::logLine(CmdHelper::textColor('cyan', 'CMS cache is erased!'));
        }

        CmdHelper::logLine(CmdHelper::textColor('light_green', 'Done!'));

        return true;
    }
}
