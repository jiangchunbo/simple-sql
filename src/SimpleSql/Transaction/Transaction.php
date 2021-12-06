<?php

namespace Tqxxkj\SimpleSql\Transaction;


use Tqxxkj\SimpleSql\Sql\Connection;

interface Transaction
{
    public function commit(): void;

    public function getConnection(): Connection;

    public function rollback(): void;
}
