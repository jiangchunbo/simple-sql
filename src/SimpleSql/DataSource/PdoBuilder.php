<?php

namespace Tqxxkj\SimpleSql\DataSource;

use PDO;
use PDOException;

class PdoBuilder
{
    private $available_drivers;

    /**
     * @var
     */
    private $driver = 'mysql';

    /**
     * @var
     */
    private $host = 'localhost';

    /**
     * @var int
     */
    private $port = 3306;

    /**
     * @var string 默认数据库
     */
    private $database;


    /**
     * @var string 用户名
     */
    private $username;

    /**
     * @var string 密码
     */
    private $password;


    /**
     * PdoBuilder constructor.
     */
    public function __construct()
    {
        $this->available_drivers = PDO::getAvailableDrivers();
    }

    public function driver(string $driver)
    {
        if (!in_array($driver, $this->available_drivers)) {
            throw new \Exception("找不到驱动: {$driver}");
        }
        $this->driver = $driver;
    }

    public function host($host)
    {
        $this->host = $host;
    }

    /**
     * @param $port
     */
    public function port($port)
    {
        $this->port = $port;
    }

    /**
     * @param $database
     */
    public function database($database)
    {
        $this->database = $database;
    }

    /**
     * @param $username
     */
    public function username($username)
    {
        $this->username = $username;
    }

    /**
     * @param $password
     */
    public function password($password)
    {
        $this->password = $password;
    }

    /**
     * @param array|null $options
     * @return PDO
     */
    public function build($options = null)
    {
        $dsn = "$this->driver:host=$this->host;port=$this->port;dbname=$this->database";
        try {
            return new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            throw $exception;
        }
    }
}
