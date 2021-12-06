<?php

namespace Tqxxkj\SimpleSql\Session\Defaults;

use Exception;
use SqlSession;
use Tqxxkj\SimpleSql\DataSource\PooledDataSource;
use Tqxxkj\SimpleSql\Executor\SimpleExecutor;
use Tqxxkj\SimpleSql\Mapping\Environment;
use Tqxxkj\SimpleSql\Transaction\PdoTransactionFactory;

class DefaultSqlSessionFactory
{

    /**
     * @return SqlSession
     * @throws Exception
     */
    public function openSession(): SqlSession
    {
        return $this->openSessionFromDataSource();
    }

    /**
     * 使用一个池化的数据源
     * @return SqlSession
     * @throws Exception
     */
    private function openSessionFromDataSource(): SqlSession
    {
        $id = Environment::getId();
        if (!$id) {
            throw new Exception("未设置默认环境");
        }
        $dataSource = new PooledDataSource();
        $transactionFactory = new PdoTransactionFactory();
        $transaction = $transactionFactory->newTransactionForDataSource($dataSource, true);
        $executor = new SimpleExecutor($transaction);
        return new DefaultSqlSession($executor);
    }
}
