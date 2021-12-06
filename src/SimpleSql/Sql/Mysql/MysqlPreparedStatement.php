<?php

namespace Tqxxkj\SimpleSql\Sql\Mysql;

use PDO;
use PDOStatement;
use Tqxxkj\SimpleSql\Sql\PreparedStatement;

class MysqlPreparedStatement implements PreparedStatement
{
    /**
     * @var PDOStatement 被包装的 PDOStatement
     */
    private $pdoStatement;

    /**
     * @var int
     */
    private $lastInsertId;

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
        $this->connection = $connection;
        $this->pdoStatement = $pdoStatement;
    }


    public function executeQuery(): array
    {
        $this->pdoStatement->execute();
        return $this->pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executeUpdate(): int
    {
        $this->pdoStatement->execute();
        // 如果是批量 insert，这里返回的是第一个 id
        $this->lastInsertId = $this->connection->getPdo()->lastInsertId();
        return $this->pdoStatement->rowCount();
    }

    public function setString(int $parameterIndex, string $x): void
    {
        $this->pdoStatement->bindValue($parameterIndex, $x);
    }

    public function setInt(int $parameterIndex, string $x): void
    {
        $this->pdoStatement->bindValue($parameterIndex, $x, PDO::PARAM_INT);
    }

    /**
     * 获得自增主键
     * TODO 后期兼容批量 insert
     * @return int[]
     */
    public function getGeneratedKeys(): array
    {
        $lastInsertId = $this->lastInsertId;
        $this->lastInsertId = [];
        return [$lastInsertId];
    }
}