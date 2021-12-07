<?php


namespace Tqxxkj\SimpleSql\Sql;


use PDO;

interface Connection
{
    const TRANSACTION_NONE = 0;

    const TRANSACTION_READ_UNCOMMITTED = 1;

    const TRANSACTION_READ_COMMITTED = 2;

    const TRANSACTION_REPEATABLE_READ = 4;

    const TRANSACTION_SERIALIZABLE = 8;

    function prepareStatement(string $sql): PreparedStatement;

    function setAutoCommit(bool $autoCommit): void;

    function getAutoCommit(): bool;

    function commit(): void;

    function rollback(): void;

    function setTransactionIsolation(int $level): void;

    function getTransactionIsolation(): int;
}