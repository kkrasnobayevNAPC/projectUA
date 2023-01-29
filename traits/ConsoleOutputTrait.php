<?php

namespace app\traits;

use yii\helpers\BaseConsole;

trait ConsoleOutputTrait {

    private static function newLine()
    {
        self::line(PHP_EOL);
    }

    /**
     * @param string $string
     * @return void
     */
    private static function sameLine(string $string)
    {
        self::line("\r$string");
    }

    /**
     * @param string $string
     * @param array $args
     * @return void
     */
    private static function line(string $string, array $args = [])
    {

        $string = BaseConsole::ansiFormat($string, $args);

        BaseConsole::stdout("$string");

    }

}