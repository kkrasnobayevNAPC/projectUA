<?php

namespace app\commands;

use app\components\TenderDataSource;
use app\components\TenderImporter;
use app\traits\ConsoleLogTrait;
use app\traits\ConsoleOutputTrait;
use Throwable;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;

class TenderController extends Controller
{

    use ConsoleLogTrait;
    use ConsoleOutputTrait;

    /**
     * @var bool whether to save full tenders data in to tenders.log
     */
    public $verbose = false;

    /**
     * @param $actionID
     * @return string[]
     */
    public function options($actionID): array
    {
        return ['help', 'verbose'];
    }

    /**
     * @return string[]
     */
    public function optionAliases(): array
    {
        return array_merge(parent::optionAliases(), ['v' => 'verbose']);
    }

    /**
     * @return int
     */
    public function actionIndex(): int
    {

        /**
         * enable "trace" log, which contains tenders data, obtained from if --verbose parameter is set
         */
        if ($this->verbose) {
            Yii::$app->getLog()->targets[1]->levels = ['error', 'warning', 'info', 'trace'];
        }

        /**
         * NOTE: all tenders import errors will be logged in tenders.log
         */
        try {

            (new TenderImporter(new TenderDataSource()))->run();

            return ExitCode::OK;

        } catch (Throwable $exception) {

            $errorString = get_class($exception) . ": {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()}";

            self::error($errorString);

            $stackTrace = 'Stack trace:' . PHP_EOL . $exception->getTraceAsString();

            self::error($stackTrace);

            self::line("SOMETHING WENT WRONG: $errorString", [BaseConsole::FG_RED]);

            self::newLine();

            self::line($stackTrace);

            self::newLine();

            return ExitCode::UNSPECIFIED_ERROR;

        }

    }

}
