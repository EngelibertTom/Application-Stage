<?php

namespace App\Manager;

abstract class DataManager
{
    abstract public function getList(bool $reverse = false);

    public function get(int $id): ?string
    {
        return $this->getList()[$id] ?? null;
    }

    public function find(string $value): ?int
    {
        return $this->getList(true)[$value] ?? null;
    }
}
