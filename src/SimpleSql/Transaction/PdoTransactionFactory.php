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
class PdoTransactionFactory
{


    /**
     * TransactionFactory constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param PDO $connection
     * @return PdoTransaction
     * @throws Exception
     */
    public function newTransactionForPDO(PDO $connection)
    {
        return new PdoTransaction($connection);
    }

    /**
     * @param DataSource $dataSource
     * @param bool       $autoCommit
     * @return PdoTransaction
     * @throws Exception
     */
    public function newTransactionForDataSource(DataSource $dataSource, bool $autoCommit)
    {
        return new PdoTransaction($dataSource, $autoCommit);
    }

}
