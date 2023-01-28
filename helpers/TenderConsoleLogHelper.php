<?php

namespace app\helpers;

use Yii;
use yii\helpers\BaseConsole;

class TenderConsoleLogHelper
{

    /**
     * @param string|array $message
     * @return void
     */
    public static function info($message)
    {
        Yii::info($message, 'tenders');
    }

    /**
     * @param string|array $message
     * @return void
     */
    public static function debug($message)
    {
        Yii::debug($message, 'tenders');
    }

    /**
     * @param string|array $message
     * @return void
     */
    public static function error($message)
    {
        Yii::error($message, 'tenders');
    }


}
