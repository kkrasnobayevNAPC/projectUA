<?php

namespace app\components;

use app\helpers\ConsoleHelper;
use app\models\Tender;
use yii\console\Exception;

class TenderImporter
{

    /**
     * @var DataSourceInterface
     */
    private $dataSource;

    public function __construct(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function run()
    {

        /**
         * drop all existing tenders to start anew
         */
        Tender::deleteAll();

        /**
         * process tenders while we get any
         */
        while ($tenders = $this->dataSource->getAll()) {
            $this->processTenders($tenders);
        }

    }

    /**
     * @param array $rawTenders
     * @return void
     * @throws Exception
     */
    private function processTenders(array $rawTenders)
    {

        $count = count($rawTenders);

        $index = 1;

        if ($count) ConsoleHelper::newLine();

        /**
         * loop through tenders
         */
        foreach ($rawTenders as $rawTender) {

            ConsoleHelper::sameLine("Processing tender $index out of $count");

            /**
             * get tender details from api
             */
            $tenderData = $this->dataSource->getOne($rawTender->id);

            /**
             * prepare tender model
             */
            $tender = new Tender();

            $tender->tenderId = $tenderData->id;
            $tender->description = $tenderData->title;
            $tender->amount = (double)$tenderData->value->amount;
            $tender->dateModified = $tenderData->dateModified;

            /**
             * if we could not save tender to db - throw exception
             */
            if (!$tender->save()) {

                ConsoleHelper::newLine();

                throw new Exception("Could not save tender into db." . PHP_EOL . json_encode($tender->getErrors()));

            }

            $index++;

        }

    }

}