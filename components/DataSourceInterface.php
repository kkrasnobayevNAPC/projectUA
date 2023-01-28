<?php

namespace app\components;

use stdClass;

interface DataSourceInterface
{

    /**
     * @return stdClass[]|null
     */
    public function getAll(): ?array;

    /**
     * @param string $id
     * @return array
     */
    public function getOne(string $id): stdClass;

}