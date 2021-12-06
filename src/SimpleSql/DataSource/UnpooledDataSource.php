<?php

namespace Tqxxkj\SimpleSql\DataSource;

use Exception;
use Tqxxkj\SimpleSql\Mapping\Environment;
use Tqxxkj\SimpleSql\Sql\Connection;
use Tqxxkj\SimpleSql\Sql\Mysql\MysqlConnection;

/**
 * Class SimpleDataSource
 * 一个没有池化的数据源
 * @package Tqxxkj\SimpleSql\DataSource
 */
class UnpooledDataSource implements DataSource
{
    private $driver;

    private $host;

    private $username;

    private $password;

    private $port;

    private $database;

    /**
     * UnpooledDataSource constructor.
     * @param $properties
     */
    public function __construct($properties)
    {
        $this->driver = $properties['driver'];
        $this->host = $properties['host'];
        $this->username = $properties['username'];
        $this->password = $properties['password'];
        $this->port = $properties['port'];
        $this->database = $properties['database'];
    }


    /**
     * 获取一个新的 Connection
     * @return Connection
     * @throws Exception
     */
    public function getConnection(): Connection
    {
        $pdoBuilder = new PdoBuilder();
        $pdoBuilder->driver($this->driver);
        $pdoBuilder->host($this->host);
        $pdoBuilder->port($this->port);
        $pdoBuilder->database($this->database);
        $pdoBuilder->username($this->username);
        $pdoBuilder->password($this->password);
        $pdo = $pdoBuilder->build();
        return new MysqlConnection($pdo);
    }
}
