<?php

namespace Tqxxkj\SimpleSql\Transaction;

use Exception;
use PDO;
use ReflectionClass;
use Tqxxkj\SimpleSql\DataSource\DataSource;

/**
 * Class SimpleTransaction
 * @package Tqxxkj\SimpleSql\Transaction
 */
class PdoTransaction implements Transaction
{
    /**
     * @var PDO
     */
    private $connection;

    /**
     * @var DataSource
     */
    private $dataSource;


    /**
     * @var bool 是否自动提交事务
     */
    private $autoCommit;


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
     * 传入一个连接
     * @param PDO $connection
     */
    public function __constructForPDO(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * 传入一个数据源
     * @param DataSource $dataSource
     * @param bool $autoCommit
     */
    public function __constructForDataSource(DataSource $dataSource, bool $autoCommit)
    {
        $this->dataSource = $dataSource;
        $this->autoCommit = $autoCommit;
    }


    /**
     * @return PDO
     * @throws Exception
     */
    public function getConnection(): PDO
    {
        if ($this->connection == null) {
            $this->openConnection();
        }
        return $this->connection;
    }


    /**
     * @return PDO
     * @throws Exception
     */
    public function openConnection()
    {
        $this->connection = $this->dataSource->getConnection();
        if ((bool)$this->connection->getAttribute(PDO::ATTR_AUTOCOMMIT) !== $this->autoCommit) {
            $this->connection->setAttribute(PDO::ATTR_AUTOCOMMIT, (int)$this->autoCommit);
        }
        return $this->connection;
    }

    function commit(): void
    {
        if ($this->connection != null && !$this->connection->getAttribute(PDO::ATTR_AUTOCOMMIT)) {
            $this->connection->commit();
        }
    }

    function rollback(): void
    {
        if ($this->connection != null && !$this->connection->getAttribute(PDO::ATTR_AUTOCOMMIT)) {
            $this->connection->rollBack();
        }
    }
}
