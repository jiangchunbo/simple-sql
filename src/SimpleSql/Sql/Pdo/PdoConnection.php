<?php


namespace Tqxxkj\SimpleSql\Sql\Pdo;


use PDO;
use Tqxxkj\SimpleSql\Sql\Connection;
use Tqxxkj\SimpleSql\Sql\PreparedStatement;

abstract class PdoConnection implements Connection
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * PdoConnection constructor.
     * @param PDO $pdo 需要包装的 PDO 类
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    function prepareStatement(string $sql): PreparedStatement
    {
        $pdoStatement = $this->pdo->prepare($sql, [
            PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL
        ]);
        return new PdoPreparedStatement($pdoStatement);
    }

    /**
     * 如果设置为非自动提交，则立即开启一个事务
     * @param bool $autoCommit
     */
    function setAutoCommit(bool $autoCommit): void
    {
        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, (int)$autoCommit);
        if (!$autoCommit) {
            $this->pdo->beginTransaction();
        }
    }

    function getAutoCommit(): bool
    {
        return (bool)$this->pdo->getAttribute(PDO::ATTR_AUTOCOMMIT);
    }

    function commit(): void
    {
        $this->pdo->commit();
    }

    function rollback(): void
    {
        $this->pdo->rollBack();
    }

    function getPdo(): PDO
    {
        return $this->pdo;
    }
}