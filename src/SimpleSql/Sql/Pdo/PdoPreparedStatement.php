<?php

namespace Tqxxkj\SimpleSql\Sql\Pdo;

use PDO;
use PDOStatement;
use Tqxxkj\SimpleSql\Sql\PreparedStatement;

class PdoPreparedStatement implements PreparedStatement
{

    /**
     * @var PDOStatement
     */
    protected $pdoStatement;

    /**
     * PdoPreparedStatement constructor.
     * @param PDOStatement $pdoStatement
     */
    public function __construct(PDOStatement $pdoStatement)
    {
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
        return $this->pdoStatement->rowCount();
    }

    public function setString($param, string $x): void
    {
        $this->pdoStatement->bindValue($param, $x);
    }

    public function setInt($param, int $x): void
    {
        $this->pdoStatement->bindValue($param, $x, PDO::PARAM_INT);
    }

    /**
     * 需要子类实现
     * @return array
     */
    public function getGeneratedKeys(): array
    {
        return [];
    }
}