<?php

namespace Tqxxkj\SimpleSql\DataSource;

class SimpleExecutor
{
    /**
     * @var SimpleTransaction
     */
    private $transaction;

    /**
     * SimpleExecutor constructor.
     * @param SimpleTransaction $transaction
     */
    public function __construct(SimpleTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param        $sql
     * @param mixed  $parameters
     * @return array
     * @throws \Exception
     */
    public function doQuery($sql, $parameters = [])
    {
        $connection = $this->transaction->openConnection();
        $statement = $connection->prepare($sql);
        foreach ($parameters as $index => $parameter) {
            if (is_array($parameter)) {
                if (sizeof($parameter) != 2) {
                    throw new \Exception("参数个数错误");
                }
                $statement->bindValue($index, $parameter[0], $parameter[1]);
            } else {
                $statement->bindValue($index, $parameter);
            }
        }
        $statement->execute();
        return $statement->fetchAll();
    }
}
