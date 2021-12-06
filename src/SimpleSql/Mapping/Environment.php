<?php

namespace Tqxxkj\SimpleSql\Mapping;

use PDO;
use Tqxxkj\SimpleSql\DataSource\DataSource;
use Tqxxkj\SimpleSql\Transaction\PdoTransactionFactory;

class Environment
{
    /**
     * @var string 环境 ID
     */
    private $id;

    /**
     * @var DataSource
     */
    private $dataSource;

    /**
     * @var PdoTransactionFactory
     */
    private $transactionFactory;

    /**
     * @var array
     */
    private $properties;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @param $driver
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @param $database
     */
    public function setProperties($id, $driver, $host, $port, $username, $password, $database)
    {
        $this->id = $id;
        $this->properties['id'] = $id;
        $this->properties['driver'] = $driver;
        $this->properties['host'] = $host;
        $this->properties['port'] = $port;
        $this->properties['username'] = $username;
        $this->properties['password'] = $password;
        $this->properties['database'] = $database;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
