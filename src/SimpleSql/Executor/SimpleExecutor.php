<?php

namespace Tqxxkj\SimpleSql\Executor;

use Exception;
use PDO;
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
     * @param        $sql
     * @param mixed  $parameters
     * @return array
     * @throws Exception
     */
    public function doQuery(string $sql, array $parameters = [])
    {
        $connection = $this->transaction->getConnection();
        $statement = $connection->prepare($sql, [
            PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY
        ]);
        foreach ($parameters as $index => $parameter) {
            if (is_array($parameter)) {
                if (sizeof($parameter) != 2) {
                    throw new Exception("参数个数错误");
                }
                $statement->bindValue($index, $parameter[0], $parameter[1]);
            } else {
                $statement->bindValue($index, $parameter);
            }
        }
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $sql
     * @param array  $parameters
     * @return int 返回影响行数
     * @throws Exception
     */
    function doUpdate(string $sql, array $parameters): int
    {
        $statement = $this->transaction->getConnection()->prepare($sql, $parameters);
        $statement->execute();
        return $statement->rowCount();
    }

    function queryCursor($sql, $parameters)
    {
        // TODO: Implement queryCursor() method.
    }
}
