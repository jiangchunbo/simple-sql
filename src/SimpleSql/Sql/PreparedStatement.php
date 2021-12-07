<?php


namespace Tqxxkj\SimpleSql\Sql;


interface PreparedStatement extends Statement
{
    public function executeQuery(): array;

    public function executeUpdate(): int;

    /**
     *
     * @param string|int $param 可以绑定数字索引，也可以绑定名称
     * @param string     $x
     */
    public function setString($param, string $x): void;

    /**
     * @param string|int $param 可以绑定数字索引，也可以绑定名称
     * @param int        $x
     */
    public function setInt($param, int $x): void;
}
