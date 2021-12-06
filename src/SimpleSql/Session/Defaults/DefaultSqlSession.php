<?php

namespace Tqxxkj\SimpleSql\Session\Defaults;

use Exception;
use SqlSession;
use Tqxxkj\SimpleSql\Executor\Executor;
use Tqxxkj\SimpleSql\Sql\Connection;

class DefaultSqlSession implements SqlSession
{
    /**
     * @var Executor
     */
    private Executor $executor;

    /**
     * SimpleSqlSession constructor.
     * @param $executor
     */
    public function __construct($executor)
    {
        $this->executor = $executor;
    }


    /**
     * @param string $sql
     * @param array  $parameters
     * @return mixed
     * @throws Exception
     */
    public function selectOne($sql, $parameters): mixed
    {
        $list = $this->selectList($sql, $parameters);
        if (sizeof($list) == 1) {
            return $list[0];
        } elseif (sizeof($list) > 1) {
            throw new Exception("结果集数量大于 1");
        } else {
            return null;
        }
    }

    /**
     * @param       $sql
     * @param array $parameters
     * @return array
     * @throws Exception
     */
    public function selectList($sql, $parameters = []): array
    {
        return $this->executor->doQuery($sql, $parameters);
    }

    public function insert(string $sql, array $parameters): int
    {
        return $this->executor->update($sql, $parameters);
    }

    public function update(string $sql, array $parameters): int
    {
        return $this->executor->update($sql, $parameters);
    }

    public function delete(string $sql, array $parameters): int
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
