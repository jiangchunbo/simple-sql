<?php

namespace Tqxxkj\SimpleSql\Mapping;

use PDO;
use Tqxxkj\SimpleSql\DataSource\DataSource;
use Tqxxkj\SimpleSql\Transaction\PdoTransactionFactory;

class Environment
{
    private static $id;

    /**
     * @var DataSource
     */
    private static $dataSource;

    /**
     * @var PdoTransactionFactory
     */
    private static $transactionFactory;

    /**
     * @var array
     */
    private static $properties;

    /**
     * @return mixed
     */
    public static function getId()
    {
        return self::$id;
    }

    public static function setProperties($id, $driver, $host, $port, $username, $password, $database)
    {
        self::$id = $id;
        self::$properties['driver'] = $driver;
        self::$properties['host'] = $host;
        self::$properties['port'] = $port;
        self::$properties['username'] = $username;
        self::$properties['password'] = $password;
        self::$properties['database'] = $database;
    }

    /**
     * @return array
     */
    public static function getProperties()
    {
        return self::$properties;
    }
}
