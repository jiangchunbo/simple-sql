<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Query;

use Tqxxkj\SimpleSql\Core\Conditions\AbstractWrapper;

class QueryWrapper extends AbstractWrapper
{
    /**
     * @var string select 子句的内容
     */
    public $sql_select;

    public function __construct()
    {
        $this->sql_select = '*';
    }

    public static function get()
    {
        return new QueryWrapper();
    }

    /**
     * 需要查询的列
     * @param mixed ...$columns
     * @return $this
     */
    public function select(...$columns)
    {
        if ($columns) {
            $this->sql_select = sprintf('%s', join(",", $columns));
        }
        return $this;
    }
}
