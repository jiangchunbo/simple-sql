<?php

namespace Tqxxkj\SimpleSql\DataSource;

use Exception;
use PDO;
use PDOException;

class PdoBuilder
{
    /**
     * @var array 缓存的可用驱动
     */
    private $availableDrivers;

    /**
     * @var string
     */
    private $driver = 'mysql';

    /**
     * @var string
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
        $this->availableDrivers = PDO::getAvailableDrivers();
    }

    /**
     * @param string $driver
     * @throws Exception
     */
    public function driver(string $driver)
    {
        if (!in_array($driver, $this->availableDrivers)) {
            throw new Exception("找不到驱动: $driver");
        }
        $this->driver = $driver;
    }

    /**
     * @param string $host
     */
    public function host(string $host)
    {
        $this->host = $host;
    }

    /**
     * @param int $port
     */
    public function port(int $port)
    {
        $this->port = $port;
    }

    /**
     * @param string $database
     */
    public function database(string $database)
    {
        $this->database = $database;
    }

    /**
     * @param string $username
     */
    public function username(string $username)
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function password(string $password)
    {
        $this->password = $password;
    }

    /**
     * @param array|null $options
     * @return PDO
     */
    public function build($options = null): PDO
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
