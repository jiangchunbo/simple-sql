<?php


namespace Tqxxkj\SimpleSql\Sql;


use Exception;
use PDO;
use PDOStatement;

class MysqlConnection implements Connection
{
    /**
     * @var int 数据库的隔离级别
     */
    private int $transactionIsolationLevel;

    /**
     * @var PDO PDO 对象
     */
    private PDO $pdo;


    /**
     * @var array 用于将 MySQL 返回的隔离级别名称转换为 int 值
     */
    private array $mapTransIsolationNameToValue = [
        'READ-UNCOMMITTED' => self::TRANSACTION_READ_UNCOMMITTED,
        'READ-COMMITTED' => self::TRANSACTION_READ_COMMITTED,
        'REPEATABLE-READ' => self::TRANSACTION_REPEATABLE_READ,
        'SERIALIZABLE' => self::TRANSACTION_SERIALIZABLE,
    ];

    /**
     * MysqlConnection constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $sql
     * @return PDOStatement
     */
    function prepareStatement(string $sql): PDOStatement
    {
        return $this->pdo->prepare($sql, [
            PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY
        ]);
    }

    function setAutoCommit(bool $autoCommit): void
    {
        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, (int)$autoCommit);
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

    /**
     * @param int $level
     * @throws Exception
     */
    function setTransactionIsolation(int $level): void
    {
        switch ($level) {
            case self::TRANSACTION_READ_UNCOMMITTED:
                $this->pdo->query('set session transaction isolation level read uncommitted');
                break;
            case self::TRANSACTION_READ_COMMITTED:
                $this->pdo->query('set session transaction isolation level read committed');
                break;
            case self::TRANSACTION_REPEATABLE_READ:
                $this->pdo->query('set session transaction isolation level repeatable read');
                break;
            case self::TRANSACTION_SERIALIZABLE:
                $this->pdo->query('set session transaction isolation level serializable');
                break;
            default:
                throw new Exception("参数错误");
        }
        $this->transactionIsolationLevel = $level;
    }

    function getTransactionIsolation(): int
    {
        if (!$this->transactionIsolationLevel) {
            $s = $this->pdo->query('select @@session.transaction_isolation', PDO::FETCH_COLUMN);
            $this->transactionIsolationLevel = $this->mapTransIsolationNameToValue[$s];
        }
        return $this->transactionIsolationLevel;
    }
}
