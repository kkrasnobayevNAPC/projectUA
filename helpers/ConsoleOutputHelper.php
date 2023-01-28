<?php

namespace app\helpers;

use Yii;
use yii\helpers\BaseConsole;

class ConsoleOutputHelper
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
     * @param array $args
     * @return void
     */
    public static function line(string $string, array $args = [])
    {

        $string = BaseConsole::ansiFormat($string, $args);

        BaseConsole::stdout("$string");

    }

}
