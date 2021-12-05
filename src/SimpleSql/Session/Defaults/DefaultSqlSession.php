<?php

namespace Tqxxkj\SimpleSql\Session\Defaults;

use Exception;
use Tqxxkj\SimpleSql\Executor\SimpleExecutor;

class DefaultSqlSession
{
    /**
     * @var SimpleExecutor
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


    /**
     * @param string $sql
     * @param array  $parameters
     * @return mixed|null
     * @throws Exception
     */
    public function selectOne($sql, $parameters)
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

    public function selectList($sql, $parameters = [])
    {
        return $this->executor->doQuery($sql, $parameters);
    }
}
