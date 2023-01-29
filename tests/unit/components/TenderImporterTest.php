<?php

use app\components\TenderDataSource;
use app\components\TenderImporter;
use app\models\Tender;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;

class TenderImporterTest extends Unit
{

    public function _tearDown()
    {
        parent::_tearDown();

        Tender::deleteAll();

    }

    /**
     * @return void
     * @throws Exception
     */
    public function testImportNotUniqueTender()
    {

        $dataSourceMock = $this->getMockBuilder(TenderDataSource::class)
            ->setConstructorArgs(['pages' => 2, 'perPage' => 10])
            ->onlyMethods(['getAll', 'getOne'])->getMock();


        $dataSourceMock->method('getAll')->willReturn(json_decode(file_get_contents(codecept_data_dir() . 'tenders.json'), true)['data']);

        /**
         * Exception will be thrown because TenderImporter will try to save 2 tenders with the same id's
         */
        $this->expectException(Exception::class);

        (new TenderImporter($dataSourceMock))->run();

    }

    /**
     * NOTE: this tails fails, need more time to investigate why
     * @return void
     * @throws Exception
     */
    public function testImportData()
    {
        (new TenderImporter(new TenderDataSource(1, 1)))->run();

        /**
         * make sure that tender got inserted into db
         */
        verify(Tender::findAll([]))->arrayCount(1);

    }

    /**
     * @return void
     * @throws Exception
     */
    public function testImportInvalidTender()
    {

        $dataSourceMock = $this->getMockBuilder(TenderDataSource::class)
            ->setConstructorArgs(['pages' => 1, 'perPage' => 1])
            ->onlyMethods(['getOne'])->getMock();

        $tender = json_decode(file_get_contents(codecept_data_dir() . 'tender.json'), true)['data'];

        $tender['id'] = 'too short';
        $tender['value']['amount'] = -1;

        $dataSourceMock->method('getOne')->willReturn($tender);

        /**
         * Exception will be thrown because TenderImporter will try to save a tender with id and amount that do not
         * mach model rules
         */
        $this->expectException(Exception::class);

        (new TenderImporter($dataSourceMock))->run();


    }

}