<?php

namespace Tqxxkj\SimpleSql\DataSource;

use Exception;

class SqlSessionFactory
{

    /**
     * @return SimpleSqlSession
     * @throws Exception
     */
    public function openSession()
    {
        return $this->openSessionFromDataSource();
    }

    /**
     * @return SimpleSqlSession
     * @throws Exception
     */
    private function openSessionFromDataSource()
    {
        $id = Environment::getId();
        if (!$id) {
            throw new Exception("未设置默认环境");
        }
        $dataSource = new SimpleDataSource();
        $transaction = new SimpleTransaction($dataSource);
        $executor = new SimpleExecutor($transaction);
        return new SimpleSqlSession($executor);
    }
}
