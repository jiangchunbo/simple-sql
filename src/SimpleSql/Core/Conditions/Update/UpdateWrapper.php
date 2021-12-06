<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Update;

use Tqxxkj\SimpleSql\Core\Conditions\AbstractWrapper;

class UpdateWrapper extends AbstractWrapper
{
    /**
     * @var array
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
        $this->addBindValues($value);
        return $this;
    }
}