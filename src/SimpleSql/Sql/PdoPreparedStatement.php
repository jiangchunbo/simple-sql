<?php


namespace Tqxxkj\SimpleSql\Sql;


use PDO;
use PDOStatement;

class PdoPreparedStatement implements PreparedStatement
{
    /**
     * @var PDOStatement 被包装的 PDOStatement
     */
    private PDOStatement $statement;

    private array $parameters;

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * PdoPreparedStatement constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    public function executeQuery(): array
    {
        foreach ($this->parameters as $parameter) {
            if (sizeof($parameter) > 2) {
                $this->statement->bindValue($parameter[0], $parameter[1], $parameter[2]);
            } else {
                $this->statement->bindValue($parameter[0], $parameter[1]);
            }
        }
        $this->statement->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executeUpdate(): int
    {
        foreach ($this->parameters as $parameter) {
            if (sizeof($parameter) > 2) {
                $this->statement->bindValue($parameter[0], $parameter[1], $parameter[2]);
            } else {
                $this->statement->bindValue($parameter[0], $parameter[1]);
            }
        }
        $this->statement->execute();
        return $this->statement->rowCount();
    }

    public function setString(int $parameterIndex, string $x): void
    {
        $this->statement->bindValue($parameterIndex, $x);
    }

    public function setInt(int $parameterIndex, string $x): void
    {
        $this->statement->bindValue($parameterIndex, $x, PDO::PARAM_INT);
    }
}