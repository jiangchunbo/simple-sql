<?php


namespace Tq\Meal\Model\Common;

class UpdateWrapper extends AbstractWrapper
{
    /**
     * @var array
     */
    public $sql_set = [];

    public static function newInstance()
    {
        return new UpdateWrapper();
    }


    public function set($column, $value, $condition = true)
    {
        if (!$condition) {
            return $this;
        }
        $this->sql_set[] = "`{$column}`=?";
        $this->addBindValues($value);
        return $this;
    }
}