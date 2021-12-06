<?php


namespace Tqxxkj\SimpleSql\Core\Conditions\Update;

use Tq\Meal\Model\Common\AbstractWrapper;

class UpdateWrapper extends AbstractWrapper
{
    /**
     * @var array
     */
    public array $sqlSet = [];

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