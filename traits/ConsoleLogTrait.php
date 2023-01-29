<?php
namespace app\traits;

use Yii;

trait ConsoleLogTrait {

    /**
     * @param string|array $message
     * @return void
     */
    private static function info($message)
    {
        Yii::info($message, 'tenders');
    }

    /**
     * @param string|array $message
     * @return void
     */
    private static function debug($message)
    {
        Yii::debug($message, 'tenders');
    }

    /**
     * @param string|array $message
     * @return void
     */
    private static function error($message)
    {
        Yii::error($message, 'tenders');
    }

}