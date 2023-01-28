<?php

namespace app\components;

use app\helpers\ConsoleHelper;
use stdClass;
use yii\console\Exception;
use linslin\yii2\curl;

class TenderDataSource implements DataSourceInterface
{

    private const LIST_URL = 'https://public.api.openprocurement.org/api/2.5/tenders?descending=1';
    private const ITEM_URL = 'https://public.api.openprocurement.org/api/0/tenders/';
    private const MAX_PAGES = 10;

    private $url;
    private $pagesReceived = 0;

    public function __construct()
    {
        $this->url = self::LIST_URL;
    }

    /**
     * @return null|array
     * @throws Exception
     */
    public function getAll(): ?array
    {

        ConsoleHelper::newLine();

        /**
         * if we already received all pages - return;
         */
        if ($this->pagesReceived === self::MAX_PAGES) {

            ConsoleHelper::line("No more tenders available", true);

            ConsoleHelper::newLine();

            return null;

        }

        $this->pagesReceived++;

        ConsoleHelper::line("Getting tenders from \"$this->url\"", true);

        /**
         * get batch of tenders
         */
        $tenders = $this->getDataFromApi($this->url);

        /**
         * if we have tenders - switch url to the next page and return tenders;
         * otherwise we have no more tenders available
         */
        if (isset($tenders->data) && count($tenders->data)) {

            /**
             * update url to get next batch of tenders
             */
            $this->url = $tenders->next_page->uri;

            return $tenders->data;

        } else {
            $this->pagesReceived = static::MAX_PAGES;
        }

        return null;

    }

    /**
     * @param string $id
     * @return stdClass
     * @throws Exception
     */
    public function getOne(string $id): stdClass
    {

        $tenderData = $this->getDataFromApi(self::ITEM_URL . $id);

        if (isset($tenderData->data)) return $tenderData->data;

        throw new Exception("Could not get tender info." .
            (isset($tenderData->errors) ? PHP_EOL . json_encode($tenderData->errors) : ''));

    }

    /**
     * @param string $url
     * @return stdClass
     * @throws Exception
     */
    private function getDataFromApi(string $url): stdClass
    {

        $curl = new curl\Curl();

        try {
            $response = $curl->get($url);
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode(), $ex);
        }

        if ($curl->errorCode) {
            throw new Exception("Error getting data from \"$url\". $curl->errorText", $curl->errorCode);
        }

        switch ($curl->responseCode) {

            case 200:

                if ($data = json_decode($response)) {
                    return $data;
                }

                throw new Exception('Invalid json provided in response');

            default:
                throw new Exception("Error getting data from \"$url\". $curl->errorText", $curl->responseCode);

        }

    }

}