<?php

namespace app\components;

use app\helpers\TenderConsoleLogHelper;
use app\helpers\ConsoleOutputHelper;
use Exception;
use linslin\yii2\curl;
use yii\helpers\BaseConsole;

class TenderDataSource implements DataSourceInterface
{

    private const LIST_URL = 'https://public.api.openprocurement.org/api/2.5/tenders?descending=1&limit=5';
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

        ConsoleOutputHelper::newLine();

        /**
         * if we already received all pages - return;
         */
        if ($this->pagesReceived === self::MAX_PAGES) {

            TenderConsoleLogHelper::info('No more tenders available. Bye!');

            ConsoleOutputHelper::line('No more tenders available', [BaseConsole::BOLD]);

            ConsoleOutputHelper::newLine();

            return null;

        }

        $this->pagesReceived++;

        TenderConsoleLogHelper::info("Getting tenders from \"$this->url\"");

        ConsoleOutputHelper::line("Getting tenders from \"$this->url\"", [BaseConsole::BOLD]);

        /**
         * get batch of tenders
         */
        $tenders = $this->getDataFromApi($this->url);

        $tendersCount = count($tenders['data']);

        /**
         * if we have tenders - switch url to the next page and return tenders;
         * otherwise we have no more tenders available
         */
        if ($tendersCount) {

            TenderConsoleLogHelper::info("$tendersCount tenders found");

            /**
             * update url to get next batch of tenders
             */
            $this->url = $tenders['next_page']['uri'];

            return $tenders['data'];

        } else {
            $this->pagesReceived = static::MAX_PAGES;
        }

        return null;

    }

    /**
     * @param string $id
     * @return array
     * @throws Exception
     */
    public function getOne(string $id): array
    {

        TenderConsoleLogHelper::info("Tender \"$id\" - obtaining data");

        return $this->getDataFromApi(self::ITEM_URL . $id)['data'];

    }

    /**
     * @param string $url
     * @return array
     * @throws Exception
     */
    private function getDataFromApi(string $url): array
    {

        $curl = new curl\Curl();

        /**
         * perform curl request
         */
        $response = $curl->get($url);

        /**
         * if we have errorCode - throw exception
         */
        if ($curl->errorCode) {
            throw new Exception("Error connecting to \"$url\". $curl->errorText", $curl->errorCode);
        }

        /**
         * if response code is 200 - try decoding the data and returning it;
         * otherwise throw exception
         */
        switch ($curl->responseCode) {

            case 200:

                if ($data = json_decode($response, true)) {

                    TenderConsoleLogHelper::debug($data);

                    return $data;

                }

                TenderConsoleLogHelper::debug("Failed response:\n$response");

                throw new Exception("Invalid json provided in response");

            default:

                TenderConsoleLogHelper::debug("Failed response:\n$response");

                throw new Exception("Response failed with code $curl->responseCode");

        }

    }

}