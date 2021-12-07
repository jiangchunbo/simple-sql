<?php

namespace Tqxxkj\SimpleSql\Transaction;

use Exception;
use PDO;
use RuntimeException;
use Tqxxkj\SimpleSql\DataSource\DataSource;

/**
 * Class TransactionFactory
 * @package Tqxxkj\SimpleSql\Transaction
 */
class PdoTransactionFactory implements TransactionFactory
{
    /**
     * TransactionFactory constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param PDO $connection
     * @return Transaction
     * @throws Exception
     */
    public function newTransactionForPDO(PDO $connection): Transaction
    {
        return new PdoTransaction($connection);
    }

    /**
     * @param DataSource $dataSource
     * @param int        $level
     * @param bool       $autoCommit
     * @return PdoTransaction
     * @throws Exception
     */
    public function newTransactionForDataSource(DataSource $dataSource, int $level, bool $autoCommit): Transaction
    {
        return new PdoTransaction($dataSource, $level, $autoCommit);
    }
}

