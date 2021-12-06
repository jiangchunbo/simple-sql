<?php


namespace Tqxxkj\SimpleSql\Executor;


use Tqxxkj\SimpleSql\Transaction\Transaction;

interface Executor
{
    /**
     * 执行更新语句
     * @param string $sql
     * @param array  $parameters
     * @return int 影响行数
     */
    function update(string $sql, array &$parameters): int;

    /**
     * 执行查询语句
     * @param string $sql
     * @param array  $parameters
     * @return array 查询结果
     */
    function query(string $sql, array $parameters): array;

    /**
     * TODO
     * @param $sql
     * @param $parameters
     * @return mixed
     */
    function queryCursor($sql, $parameters);

    /**
     * 提交事务
     * @param bool $required
     */
    function commit(bool $required): void;

    /**
     * 回滚事务
     * @param bool $required
     */
    function rollback(bool $required): void;

    /**
     * 获得包装的事务
     * @return Transaction
     */
    function getTransaction(): Transaction;

}