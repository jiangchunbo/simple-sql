<?php


namespace Tqxxkj\SimpleSql\Sql;


interface PreparedStatement extends Statement
{
    public function executeQuery(): array;

    public function executeUpdate(): int;

    public function setString(int $parameterIndex, string $x): void;

    public function setInt(int $parameterIndex, string $x): void;
}
