<?php


namespace Tqxxkj\SimpleSql\Executor;


use Tqxxkj\SimpleSql\Transaction\Transaction;

interface Executor
{
    function update(string $sql, array $parameters): int;

    function query($sql, $parameters): array;

    function queryCursor($sql, $parameters);

    function getTransaction(): Transaction;

    function commit(bool $required): void;

    function rollback(bool $required): void;
}