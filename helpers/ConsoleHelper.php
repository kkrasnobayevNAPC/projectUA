<?php

namespace app\helpers;

use yii\helpers\BaseConsole;

class ConsoleHelper
{

    public static function newLine()
    {
        self::line(PHP_EOL);
    }

    /**
     * @param string $string
     * @return void
     */
    public static function sameLine(string $string)
    {
        self::line("\r$string");
    }

    /**
     * @param string $string
     * @param bool $bold
     * @return void
     */
    public static function line(string $string, bool $bold = false)
    {

        $args = $bold ? [BaseConsole::BOLD] : [];

        $string = BaseConsole::ansiFormat($string, $args);

        BaseConsole::stdout("$string");

    }

}
