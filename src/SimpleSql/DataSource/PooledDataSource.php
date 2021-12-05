<?php


namespace Tqxxkj\SimpleSql\DataSource;


use Exception;
use PDO;

/**
 * Class PooledDataSource
 * 尽管叫池化数据源，但 PHP 不涉及多线程共享的问题，因此 $idleConnections 理论只有一个，可以复用
 * @package Tqxxkj\SimpleSql\DataSource
 */
class PooledDataSource implements DataSource
{
    /**
     * @var UnpooledDataSource
     */
    private $unpooledDataSource;

    /**
     * @var array
     */
    private $idleConnections = [];

    /**
     * PooledDataSource constructor.
     */
    public function __construct()
    {
        $this->unpooledDataSource = new UnpooledDataSource();
    }


    /**
     * @return PDO
     * @throws Exception
     */
    function getConnection()
    {
        if (sizeof($this->idleConnections) == 0) {
            $connection = $this->unpooledDataSource->getConnection();
            array_push($this->idleConnections, $connection);
        }
        return $this->idleConnections[0];
    }
}