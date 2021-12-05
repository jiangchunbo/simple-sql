<?php

namespace Tqxxkj\SimpleSql\Transaction;

use PDO;

interface Transaction
{
    function getConnection(): PDO;

    function commit(): void;

    function rollback(): void;
}