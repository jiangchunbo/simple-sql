<?php

namespace Tqxxkj\SimpleSql\Transaction;

use Exception;
use PDO;
use ReflectionClass;
use Tqxxkj\SimpleSql\DataSource\Connection;
use Tqxxkj\SimpleSql\DataSource\DataSource;
use Tqxxkj\SimpleSql\DataSource\MysqlConnection;

/**
 * Class SimpleTransaction
 * @package Tqxxkj\SimpleSql\Transaction
 */
class PdoTransaction implements Transaction
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var DataSource
     */
    private DataSource $dataSource;


    /**
     * @var bool 是否自动提交事务
     */
    private bool $autoCommit;


    /**
     * SimpleTransaction constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $args = func_get_args();
        if (sizeof($args) == 0) {
            throw new Exception("构造失败");
        }
        $reflectionClass = new ReflectionClass(get_class($args[0]));
        if (in_array('Tqxxkj\SimpleSql\DataSource\DataSource', $reflectionClass->getInterfaceNames())) {
            call_user_func_array([$this, '__constructForDataSource'], $args);
        } elseif (get_class($args[0]) == 'PDO') {
            call_user_func_array([$this, '__constructForPDO'], $args);
        } else {
            throw new Exception("构造失败");
        }
    }

    /**
     * 传入一个 PDO 连接
     * @param PDO $pdo
     */
    public function __constructForPDO(PDO $pdo)
    {
        $connection = new MysqlConnection($pdo);
        $this->connection = $connection;
    }

    /**
     * 传入一个数据源
     * @param DataSource $dataSource
     * @param bool       $autoCommit
     */
    public function __constructForDataSource(DataSource $dataSource, bool $autoCommit)
    {
        $this->dataSource = $dataSource;
        $this->autoCommit = $autoCommit;
    }


    /**
     * @return Connection
     * @throws Exception
     */
    public function getConnection(): Connection
    {
        if ($this->connection == null) {
            $this->openConnection();
        }
        return $this->connection;
    }


    /**
     * @return Connection
     * @throws Exception
     */
    public function openConnection(): Connection
    {
        $this->connection = $this->dataSource->getConnection();
        if ($this->connection->getAutoCommit() !== $this->autoCommit) {
            $this->connection->setAutoCommit($this->autoCommit);
        }
        return $this->connection;
    }

    public function commit(): void
    {
        if ($this->connection != null && !$this->connection->getAutoCommit()) {
            $this->connection->commit();
        }
    }

    public function rollback(): void
    {
        if ($this->connection != null && !$this->connection->getAutoCommit()) {
            $this->connection->rollBack();
        }
    }
}
