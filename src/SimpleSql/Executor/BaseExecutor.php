<?php


namespace Tqxxkj\SimpleSql\Executor;


use Tqxxkj\SimpleSql\Transaction\Transaction;

abstract class BaseExecutor implements Executor
{
    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * BaseExecutor constructor.
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }


    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function commit(bool $required): void
    {
        if ($required) {
            $this->transaction->commit();
        }
    }

    public function rollback(bool $required): void
    {
        if ($required) {
            $this->transaction->rollback();
        }
    }


    public function query(string $sql, array $parameters): array
    {
        $list = $this->queryFromDatabase($sql, $parameters);
        return $list;
    }

    protected function queryFromDatabase($sql, $parameters)
    {
        $list = $this->doQuery($sql, $parameters);
        return $list;
    }

    abstract function doQuery(string $sql, array $parameters);

    public function update(string $sql, array &$parameters): int
    {
        return $this->doUpdate($sql, $parameters);
    }

    abstract function doUpdate(string $sql, array &$parameters);
}