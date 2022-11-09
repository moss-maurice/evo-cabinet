<?php

namespace mmaurice\cabinet\commands;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\helpers\CmdHelper;
use mmaurice\cabinet\core\prototypes\commands\CommandProtorype;

class ConsoleCommand extends CommandProtorype
{
    // php cli.php console/test foo bar
    public function actionTest($foo, $bar)
    {
        CmdHelper::drawLine(CmdHelper::textColor('white', ' Foo is "' . $foo . '"'));
        CmdHelper::drawLine(CmdHelper::textColor('white', ' Bar is "' . $bar . '"'));
        CmdHelper::drawLine();

        return true;
    }
}
