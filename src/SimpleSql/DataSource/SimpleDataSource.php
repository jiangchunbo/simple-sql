<?php

namespace Tqxxkj\SimpleSql\DataSource;

use Exception;
use PDO;

/**
 * Class SimpleDataSource
 * 一个没有池化的数据源
 * @package Tqxxkj\SimpleSql\DataSource
 */
class SimpleDataSource
{
    /**
     * SimpleDataSource constructor.
     * @param $database
     */
    public function __construct()
    {
    }


    /**
     *
     * @return PDO
     * @throws Exception
     */
    public function getConnection()
    {
        $pdoBuilder = new PdoBuilder();
        $requiredProperties = ['driver', 'host', 'username', 'password', 'port', 'database'];
        foreach ($requiredProperties as $property) {
            if (!isset(Environment::getProperties()[$property])) {
                throw new Exception("未设置环境属性: $property");
            }
            $pdoBuilder->$property(Environment::getProperties()[$property]);
        }
        return $pdoBuilder->build();
    }
}
