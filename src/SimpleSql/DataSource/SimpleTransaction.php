<?php

namespace Tqxxkj\SimpleSql\DataSource;

use Exception;
use PDO;

class SimpleTransaction
{
    /**
     * @var SimpleDataSource
     */
    private $dataSource;

    /**
     * SimpleTransaction constructor.
     * @param SimpleDataSource $dataSource
     */
    public function __construct(SimpleDataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }


    /**
     * @return PDO
     * @throws Exception
     */
    public function openConnection()
    {
        return $this->dataSource->getConnection();
    }
}
