<?php

namespace app\commands;

use app\components\TenderDataSource;
use app\components\TenderImporter;
use yii\console\Controller;
use yii\console\Exception;
use yii\console\ExitCode;

class TenderController extends Controller
{

    /**
     * @return int
     * @throws Exception
     */
    public function actionIndex(): int
    {

        (new TenderImporter(new TenderDataSource()))->run();

        return ExitCode::OK;

    }

}
