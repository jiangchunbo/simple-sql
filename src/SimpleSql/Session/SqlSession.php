<?php


use Tqxxkj\SimpleSql\Session;
use Tqxxkj\SimpleSql\Sql\Connection;

interface SqlSession
{
    public function selectOne(string $sql, array $parameters): mixed;

    public function selectList(string $sql, array $parameters): array;

    public function insert(string $sql, array $parameters): int;

    public function update(string $sql, array $parameters): int;

    public function delete(string $sql, array $parameters): int;

    public function commit(bool $force): void;

    public function rollback(bool $force): void;

    public function getConnection(): Connection;
}