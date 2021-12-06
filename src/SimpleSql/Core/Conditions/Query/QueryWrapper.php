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

    /**
     * @return QueryWrapper
     */
    public static function get(): QueryWrapper
    {
        return new QueryWrapper();
    }

    /**
     * 需要查询的列
     * @param mixed ...$columns
     * @return QueryWrapper
     */
    public function select(...$columns): QueryWrapper
    {
        if ($columns) {
            $this->sqlSelect = sprintf('%s', join(",", $columns));
        }
        return $this;
    }
}
