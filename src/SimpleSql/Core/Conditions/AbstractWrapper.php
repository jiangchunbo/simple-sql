<?php

namespace Tqxxkj\SimpleSql\Core\Conditions;

use Tqxxkj\SimpleSql\Core\Conditions\Segments\MergeSegments;

abstract class AbstractWrapper extends Wrapper
{
    /**
     * @var MergeSegments
     */
    private $express;

    /**
     * @var array 需要绑定的参数缓存
     */
    public $paramIndexValuePairs = [];

    /**
     * AbstractWrapper constructor.
     */
    public function __construct()
    {
        $this->express = new MergeSegments();
    }


    /**
     * @param string $column    列名
     * @param mixed  $value     值，单个值，或者数组[值，PDO 类型]
     * @param bool   $condition 是否需要组装该条件
     * @return $this
     */
    public function eq($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '=', '?');
        $this->addParamIndexValuePairs($value);
        return $this;
    }

    /**
     * 不等于
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function ne($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '<>', '?');
        $this->addParamIndexValuePairs($value);
        return $this;
    }

    /**
     * >
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return $this
     */
    public function gt($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '>', '?');
        $this->addParamIndexValuePairs($value);
        return $this;
    }


    /**
     * >=
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return $this
     */
    public function ge($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '>=', '?');
        $this->addParamIndexValuePairs($value);
        return $this;
    }

    /**
     * <
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function lt($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '<', '?');
        $this->addParamIndexValuePairs($value);
        return $this;
    }

    /**
     * <=
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function le($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '<=', '?');
        $this->addParamIndexValuePairs($value);
        return $this;
    }

    /**
     * '%s%'
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function like($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", 'like', "CONCAT('%',?,'%')");
        $this->addParamIndexValuePairs($value);
        return $this;
    }

    /**
     * '%s'
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function likeLeft($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", 'like', "CONCAT('%',?)");
        $this->addParamIndexValuePairs($value);
        return $this;
    }

    /**
     * 's%'
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return $this
     */
    public function likeRight($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", 'like', "CONCAT(?,'%')");
        $this->addParamIndexValuePairs($value);
        return $this;
    }


    /**
     * in 查询
     * @param       $column
     * @param       $value_list
     * @param bool  $condition
     * @return $this
     */
    public function in($column, $value_list, $condition = true): AbstractWrapper
    {
        if (!$value_list) {
            return $this;
        }
        $this->addCondition(
            $condition,
            "`$column`",
            'in',
            sprintf('(%s)', join(',', array_fill(0, sizeof($value_list), '?')))
        );
        foreach ($value_list as $value) {
            $this->addParamIndexValuePairs($value);
        }
        return $this;
    }

    public function groupBy(...$columns): AbstractWrapper
    {
        if (!$columns) {
            return $this;
        }
        $this->addCondition(true, 'group by', ...$columns);
        return $this;
    }


    /**
     * 追加排序
     * @param        $column
     * @param bool   $isAsc
     * @param bool   $condition
     * @return AbstractWrapper
     */
    public function orderBy($column, $isAsc = true, $condition = true): AbstractWrapper
    {
        if (!$column) {
            return $this;
        }
        $mode = $isAsc ? 'asc' : 'desc';
        $this->addCondition($condition, 'order by', "`$column`", $mode);
        return $this;
    }


    /**
     * 添加片段
     * @param       $condition
     * @param mixed ...$sqlSegments
     */
    public function addCondition($condition, ...$sqlSegments)
    {
        if (!$condition) {
            return;
        }
        $this->express->add(...$sqlSegments);
    }


    public function addParamIndexValuePairs($value)
    {
        $place_holder_index = sizeof($this->paramIndexValuePairs);
        $this->paramIndexValuePairs[$place_holder_index + 1] = is_array($value) ? [$value[0], $value[1]] : $value;
    }
}
