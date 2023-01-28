<?php

namespace app\components;

use stdClass;

interface DataSourceInterface
{

    /**
     * @return array|null
     */
    public function getAll(): ?array;

    /**
     * @param string $id
     * @return array
     */
    public function getOne(string $id): array;

}