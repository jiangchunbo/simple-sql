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
    public function newTransactionForPDO(PDO $connection): Transaction;

    /**
     * @param DataSource $dataSource 数据源
     * @param int        $level
     * @param bool       $autoCommit 是否自动提交
     * @return Transaction
     */
    public function newTransactionForDataSource(DataSource $dataSource, int $level, bool $autoCommit): Transaction;
}
