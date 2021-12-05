<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Query;

use Tqxxkj\SimpleSql\Core\Conditions\AbstractWrapper;

class QueryWrapper extends AbstractWrapper
{
    /**
     * @var string select 子句的内容
     */
    public $sqlSelect;

    public function __construct()
    {
        $this->sqlSelect = '*';
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
            $this->sqlSelect = sprintf('%s', join(",", $columns));
        }
        return $this;
    }
}
