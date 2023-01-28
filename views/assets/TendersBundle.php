<?php
/**
 * @category   NAPC Report Generator 3
 * @package    report_generator3
 * @author     Kirill Krasnobayev <kirill.krasnobayev@napc.com>
 * @copyright  © 2016 NAPC
 * @version    3
 * @link       https://napcdev.git.beanstalkapp.com/report_generator3.git
 */

namespace app\views\assets;

use yii\web\AssetBundle;

class TendersBundle extends AssetBundle
{

    public $sourcePath = __DIR__;

    public $css = [
        'css/tenders.css',
    ];

}