<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Update;

use Tqxxkj\SimpleSql\Core\Conditions\AbstractWrapper;

class UpdateWrapper extends AbstractWrapper
{
    /**
     * @var array 由 id=?, name=? 组成的片段
     */
    public $sqlSet = [];

    /**
     * @return UpdateWrapper
     */
    public static function get(): UpdateWrapper
    {
        return new UpdateWrapper();
    }


    public function set($column, $value, $condition = true): UpdateWrapper
    {
        if (!$condition) {
            return $this;
        }
        $this->sqlSet[] = "`{$column}`=?";
        $this->addParamIndexValuePairs($value);
        return $this;
    }

    public function getSqlSet(): string
    {
        if (sizeof($this->sqlSet) === 0) {
            return '';
        }
        return join(',', $this->sqlSet);
    }
}