<?php

namespace app\components;

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