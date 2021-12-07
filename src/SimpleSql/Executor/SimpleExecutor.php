<?php

namespace Tqxxkj\SimpleSql\Executor;

use Cassandra\Varint;
use Exception;
use PDO;
use Tqxxkj\SimpleSql\Sql\PreparedStatement;
use Tqxxkj\SimpleSql\Transaction\Transaction;

class SimpleExecutor extends BaseExecutor
{
    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * SimpleExecutor constructor.
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        parent::__construct($transaction);
    }

    /**
     * @param string $sql
     * @param array  $parameters
     * @return array
     */
    public function doQuery(string $sql, array $parameters = []): array
    {
        $connection = $this->transaction->getConnection();
        $statement = $connection->prepareStatement($sql);
        foreach ($parameters as $index => $parameter) {
            $this->setParameter($statement, $index, $parameter);
        }
        return $statement->executeQuery();
    }

    /**
     * @param string $sql
     * @param array  $parameters
     * @return int 返回影响行数
     * @throws Exception
     */
    function doUpdate(string $sql, array &$parameters): int
    {
        $connection = $this->transaction->getConnection();
        $statement = $connection->prepareStatement($sql);
        foreach ($parameters as $index => $parameter) {
            $this->setParameter($statement, $index, $parameter);
        }
        $rowCount = $statement->executeUpdate();
        if ($statement->getGeneratedKeys()) {
            $parameters['id'] = (int)$statement->getGeneratedKeys()[0];
        }
        return $rowCount;
    }

    function queryCursor($sql, $parameters)
    {
        // TODO: Implement queryCursor() method.
    }


    /**
     * 设置参数
     * @param PreparedStatement $statement
     * @param                   $key
     * @param array             $parameter
     */
    private function setParameter($statement, $key, $parameter)
    {
        switch ($parameter[1]) {
            default:
            case PDO::PARAM_STR:
                $statement->setString($key, $parameter[0]);
                break;
            case PDO::PARAM_INT:
                $statement->setInt($key, $parameter[0]);
                break;
        }
    }
}
