<?php

namespace Tqxxkj\SimpleSql\Sql\Mysql;

use PDO;
use PDOStatement;
use Tqxxkj\SimpleSql\Sql\Pdo\PdoPreparedStatement;

class MysqlPreparedStatement extends PdoPreparedStatement
{
    /**
     * @var int 保存最后一次添加数据的自增 id，或者最后一批添加数据的第一个自增 id
     */
    private $lastInsertId;

    /**
     * @var int 最后一次操作影响行数
     */
    private $rowCount;


    /**
     * @var array
     */
    private $batchedGeneratedKeys = [];

    /**
     * @var MysqlConnection
     */
    private $connection;


    /**
     * PdoPreparedStatement constructor.
     * @param MysqlConnection $connection
     * @param PDOStatement    $pdoStatement
     */
    public function __construct(MysqlConnection $connection, PDOStatement $pdoStatement)
    {
        parent::__construct($pdoStatement);
        $this->connection = $connection;
    }

    /**
     * 执行增删改，保存自增主键
     * @return int
     */
    public function executeUpdate(): int
    {
        $this->rowCount = parent::executeUpdate();
        $this->lastInsertId = $this->connection->getPdo()->lastInsertId();
        if ($this->lastInsertId) {
            $this->batchedGeneratedKeys = [];
            for ($i = 0; $i < $this->rowCount; ++$i) {
                array_push($this->batchedGeneratedKeys, $this->lastInsertId + $i);
            }
        }
        return $this->rowCount;
    }

    /**
     * 设置字符串值，默认行为
     * @param int|string $param
     * @param string     $x
     */
    public function setString($param, string $x): void
    {
        $this->pdoStatement->bindValue($param, $x);
    }

    /**
     * 设置整型值
     * @param int|string $param
     * @param int        $x
     */
    public function setInt($param, int $x): void
    {
        $this->pdoStatement->bindValue($param, $x, PDO::PARAM_INT);
    }

    /**
     * 获得自增主键
     * @return int[] 因为可以批量添加，因此返回数组
     */
    public function getGeneratedKeys(): array
    {
        return $this->batchedGeneratedKeys;
    }
}