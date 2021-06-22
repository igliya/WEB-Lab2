<?php

namespace App\Repository;

use App\Helper\DbConnection;

abstract class BaseRepository
{
    protected string $tableName;

    protected DbConnection $db;
}
