<?php

namespace App\Entity;

use App\Helper\DbConnection;

abstract class BaseEntity
{
    protected DbConnection $db;

    protected string $tableName;

    abstract public function save(): bool;

    abstract protected function create(): bool;

    abstract protected function update(): int;

    abstract protected function iterateFields(bool $includeId = true): array;

    abstract protected function getAllEntityFields(bool $camelCase = false): array;

    abstract protected function iterateColumns(bool $toNamedColumns = false, bool $includeId = true): string;

    abstract protected function iterateColumnsAndFields(): array;

    abstract protected function getCamelValue(string $value): string;
}
