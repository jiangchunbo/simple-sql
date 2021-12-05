<?php


namespace Tqxxkj\SimpleSql\Transaction;


use PDO;
use Tqxxkj\SimpleSql\DataSource\DataSource;

interface TransactionFactory
{
    /**
     *
     * @param PDO $connection 数据库连接
     * @return Transaction
     */
    function newTransactionForPDO(PDO $connection): Transaction;

    /**
     * @param DataSource $dataSource 数据源
     * @param bool       $autoCommit 是否自动提交
     * @return Transaction
     */
    function newTransactionForDataSource(DataSource $dataSource, bool $autoCommit): Transaction;
}