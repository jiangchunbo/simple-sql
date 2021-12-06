<?php

namespace Tqxxkj\SimpleSql\Session;

use Exception;
use Tqxxkj\SimpleSql\Sql\Connection;

interface SqlSession
{
    /**
     * 获得一条记录
     * @param string $sql
     * @param array  $parameters
     * @return array 如果该记录存在，返回 array；否则，返回 []
     * @throws Exception 如果记录数大于 1，需要抛出异常
     */
    public function selectOne(string $sql, array $parameters = []): array;

    /**
     * 获得多条记录
     * @param string $sql
     * @param array  $parameters
     * @return array 如果记录存在，返回 array；否则，返回 []
     * @throws Exception
     */
    public function selectList(string $sql, array $parameters = []): array;

    /**
     * 插入记录
     * @param string $sql
     * @param array  $parameters
     * @return int 返回影响行数
     * @throws Exception
     */
    public function insert(string $sql, array $parameters = []): int;

    /**
     * 更新记录
     * @param string $sql
     * @param array  $parameters
     * @return int 返回影响行数
     * @throws Exception
     */
    public function update(string $sql, array $parameters = []): int;

    /**
     * 删除记录
     * @param string $sql
     * @param array  $parameters
     * @return int
     */
    public function delete(string $sql, array $parameters = []): int;

    /**
     * 提交事务
     * @param bool $force
     */
    public function commit(bool $force): void;

    /**
     * 回滚事务
     * @param bool $force
     */
    public function rollback(bool $force): void;

    /**
     * 获得被包装的 Connection
     * @return Connection
     */
    public function getConnection(): Connection;
}