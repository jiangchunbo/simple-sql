<?php

namespace Tqxxkj\SimpleSql\DataSource;

use Tqxxkj\SimpleSql\Sql\Connection;

interface DataSource
{
    public function getConnection(): Connection;
}
