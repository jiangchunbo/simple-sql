<?php

namespace Tqxxkj\SimpleSql\DataSource;

class TransactionFactory
{
    /**
     * @param $datasource
     * @return SimpleTransaction
     */
    public function newTransaction($datasource)
    {
        return new SimpleTransaction($datasource);
    }
}
