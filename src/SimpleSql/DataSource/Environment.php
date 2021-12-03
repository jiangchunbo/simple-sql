<?php

namespace Tqxxkj\SimpleSql\DataSource;

use PDO;

class Environment
{
    private static $id;

    /**
     * @var SimpleDataSource
     */
    private static $dataSource;

    /**
     * @var TransactionFactory
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
