<?php

namespace app\components;

use app\traits\ConsoleOutputTrait;
use Exception;
use linslin\yii2\curl;
use yii\helpers\BaseConsole;
use app\traits\ConsoleLogTrait;

class TenderDataSource implements DataSourceInterface
{

    use ConsoleLogTrait;
    use ConsoleOutputTrait;

    private const LIST_URL = 'https://public.api.openprocurement.org/api/2.5/tenders?descending=1';
    private const ITEM_URL = 'https://public.api.openprocurement.org/api/0/tenders/';

    /**
     * @var string $url
     */
    private $url;

    /**
     * @var int $pages
     */
    private $pages;

    /**
     * @var int $pagesReceived
     */
    private $pagesReceived = 0;

    /**
     * @param int $pages
     * @param int $perPage
     * @throws Exception
     */
    public function __construct(int $pages = 10, int $perPage = 20)
    {

        $this->url = self::LIST_URL;

        if ($pages < 1) {
            throw new Exception('$pages value must be more than 0');
        }

        $this->pages = $pages;

        if ($perPage < 1) {
            throw new Exception('$perPage value must be more than 0');
        }

        $this->url .= "&limit=$perPage";

    }

    /**
     * @return null|array
     * @throws Exception
     */
    public function getAll(): ?array
    {

        self::newLine();

        /**
         * if we already received all pages - return;
         */
        if ($this->pagesReceived === $this->pages) {

            self::info('No more tenders available. Done!');

            self::line('No more tenders available. Done!', [BaseConsole::BOLD]);

            self::newLine();

            return null;

        }

        $this->pagesReceived++;

        self::info("Getting tenders from \"$this->url\"");

        self::line("Getting tenders from \"$this->url\"", [BaseConsole::BOLD]);

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

            self::info("$tendersCount tenders found");

            /**
             * update url to get next batch of tenders
             */
            $this->url = $tenders['next_page']['uri'];

            return $tenders['data'];

        } else {
            $this->pagesReceived = $this->pages;
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

        self::info("Tender \"$id\" - obtaining data");

        return $this->getDataFromApi(self::ITEM_URL . $id)['data'];

    }

    /**
     * @param string $url
     * @return array
     * @throws Exception
     */
    protected function getDataFromApi(string $url): array
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

                    self::debug($data);

                    return $data;

                }

                self::debug("Failed response:\n$response");

                throw new Exception("Invalid json provided in response");

            default:

                self::debug("Failed response:\n$response");

                throw new Exception("Response failed with code $curl->responseCode");

        }

    }

}