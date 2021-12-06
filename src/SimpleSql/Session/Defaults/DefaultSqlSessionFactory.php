<?php

namespace Tqxxkj\SimpleSql\Session\Defaults;

use Exception;
use Tqxxkj\SimpleSql\DataSource\PooledDataSource;
use Tqxxkj\SimpleSql\Executor\Executor;
use Tqxxkj\SimpleSql\Executor\SimpleExecutor;
use Tqxxkj\SimpleSql\Mapping\Environment;
use Tqxxkj\SimpleSql\Session\SqlSession;
use Tqxxkj\SimpleSql\Transaction\PdoTransactionFactory;

class DefaultSqlSessionFactory
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * DefaultSqlSessionFactory constructor.
     * @param $environment
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
    }


    /**
     * @param Executor|null $executor   执行器，传 null 表示使用默认的执行器
     * @param int|null      $level      事务隔离级别，null 表示使用默认事务隔离级别
     * @param bool          $autoCommit 是否自动提交
     * @return SqlSession
     * @throws Exception
     */
    public function openSession(Executor $executor = null, int $level = 0, bool $autoCommit = true): SqlSession
    {
        return $this->openSessionFromDataSource($executor, $level, $autoCommit);
    }

    /**
     * 使用一个池化的数据源
     * @param $executor
     * @param $level
     * @param $autoCommit
     * @return SqlSession
     * @throws Exception
     */
    private function openSessionFromDataSource($executor, $level, $autoCommit): SqlSession
    {
        $id = $this->environment->getProperties()['id'];
        if (!$id) {
            throw new Exception("未设置默认环境");
        }
        $dataSource = new PooledDataSource($this->environment->getProperties());
        $transactionFactory = new PdoTransactionFactory();
        $transaction = $transactionFactory->newTransactionForDataSource($dataSource, $level, $autoCommit);
        if (!isset($executor)) {
            $executor = new SimpleExecutor($transaction);
        }
        return new DefaultSqlSession($executor);
    }
}
