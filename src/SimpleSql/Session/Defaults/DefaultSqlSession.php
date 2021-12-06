<?php

namespace Tqxxkj\SimpleSql\Session\Defaults;

use Exception;
use Tqxxkj\SimpleSql\Executor\Executor;
use Tqxxkj\SimpleSql\Session\SqlSession;
use Tqxxkj\SimpleSql\Sql\Connection;

class DefaultSqlSession implements SqlSession
{
    /**
     * @var Executor
     */
    private $executor;

    /**
     * SimpleSqlSession constructor.
     * @param $executor
     */
    public function __construct($executor)
    {
        $this->executor = $executor;
    }


    public function selectOne(string $sql, array $parameters = []): array
    {
        $list = $this->selectList($sql, $parameters);
        if (sizeof($list) == 1) {
            return $list[0];
        } elseif (sizeof($list) > 1) {
            throw new Exception("结果集数量大于 1");
        } else {
            return [];
        }
    }

    public function selectList(string $sql, array $parameters = []): array
    {
        return $this->executor->doQuery($sql, $parameters);
    }

    public function insert(string $sql, array $parameters = []): int
    {
        return $this->executor->update($sql, $parameters);
    }

    public function update(string $sql, array $parameters = []): int
    {
        return $this->executor->update($sql, $parameters);
    }

    public function delete(string $sql, array $parameters = []): int
    {
        return $this->executor->update($sql, $parameters);
    }

    public function commit(bool $force): void
    {
        $this->executor->commit($force);
    }

    public function rollback(bool $force): void
    {
        $this->executor->rollback($force);
    }

    public function getConnection(): Connection
    {
        return $this->executor->getTransaction()->getConnection();
    }
}
