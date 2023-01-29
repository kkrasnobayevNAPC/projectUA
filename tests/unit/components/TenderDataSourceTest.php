<?php

namespace tests\unit\components;

use app\components\TenderDataSource;
use Codeception\Test\Unit;
use Exception;
use ReflectionClass;

class TenderDataSourceTest extends Unit
{

    public function testInvalidPages()
    {

        /**
         * Exception is thrown if the value is less than 0
         */
        $this->expectException(Exception::class);

        new TenderDataSource(0);


    }

    public function testInvalidPerPage()
    {

        $this->expectException(Exception::class);

        /**
         * Exception is thrown if the value is less than 0
         */
        new TenderDataSource(10, 0);

    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGetTenders()
    {

        $mock = $this->getMockBuilder(TenderDataSource::class)
            ->setConstructorArgs(['pages' => 2, 'perPage' => 10])
            ->onlyMethods(['getDataFromApi'])->getMock();

        $mock->method('getDataFromApi')->willReturn(json_decode(file_get_contents(codecept_data_dir() . 'tenders.json'), true));

        $index = 0;

        /**
         * make sure getAll ony runs 2 times (2 pages)
         * and every batch does contain 10 items
         */
        while ($data = $mock->getAll()) {

            verify($data)->arrayCount(10);

            $index++;

        }

        verify($index)->equals(2);

    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGetTendersWithNoData()
    {

        $mock = $this->getMockBuilder(TenderDataSource::class)
            ->onlyMethods(['getDataFromApi'])->getMock();

        $mock->method('getDataFromApi')->willReturn(['data' => []]);

        /**
         * make sure getAll returns null ig data is empty (no tenders on given page)
         */
        verify($mock->getAll())->null();

    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGetTender()
    {

        $mock = $this->getMockBuilder(TenderDataSource::class)
            ->onlyMethods(['getDataFromApi'])->getMock();

        $mock->method('getDataFromApi')->willReturn(json_decode(file_get_contents(codecept_data_dir() . 'tender.json'), true));

        /**
         * make sure we get a valid tender
         */
        verify($mock->getOne('XXXX'))->arrayHasKey('id');

    }

    /**
     * @return void
     * @throws Exception
     */
    public function testWithRealApiCalls()
    {

        $dataSource = new TenderDataSource(1, 1);

        $index = 0;

        $tenders = [];

        /**
         * make sure getAll runs only once and yields only one tender
         */
        while ($data = $dataSource->getAll()) {

            verify($data)->arrayCount(1);

            $tenders = $data;

            $index++;

        }

        verify($index)->equals(1);

        /**
         * make sure tender, we received is valid
         */
        verify($tenders[0]['id'])->isString();

        $tender = $dataSource->getOne($tenders[0]['id']);

        /**
         * make sure getOne returned valid tender
         */
        verify($tender['id'])->isString();

        /**
         * Exception is thrown if we are trying to get a tender with invalid id
         */
        $this->expectException(Exception::class);

        $dataSource->getOne('XXX');

    }

    /**
     * @return void
     * @throws Exception
     */
    public function testApiCallsWithInvalidUrl()
    {

        /**
         * Exception is thrown because api url is invalid
         */
        $this->expectException(Exception::class);

        $this->getDataSourceWithModifiedUrl('https://fake.url')->getAll();

    }

    /**
     * @return void
     * @throws Exception
     */
    public function testApiCallsWithWrongUrl()
    {

        /**
         * Exception is thrown because api url is wrong
         */
        $this->expectException(Exception::class);

        $this->getDataSourceWithModifiedUrl('https://zaphod.napc.com')->getAll();

    }

    /**
     * @param string $url
     * @return TenderDataSource
     */
    private function getDataSourceWithModifiedUrl(string $url): TenderDataSource {

        $dataSource = new TenderDataSource();

        /**
         * change private url property to a fake one
         */
        $reflector = new ReflectionClass($dataSource);
        $property = $reflector->getProperty('url');
        $property->setAccessible(true);
        $property->setValue($dataSource, $url);

        return $dataSource;

    }

}