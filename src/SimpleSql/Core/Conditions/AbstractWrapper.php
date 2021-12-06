<?php

namespace Tqxxkj\SimpleSql\Core\Conditions;

use Tqxxkj\SimpleSql\Core\Conditions\Segments\GroupBySegmentList;
use Tqxxkj\SimpleSql\Core\Conditions\Segments\HavingSegmentList;
use Tqxxkj\SimpleSql\Core\Conditions\Segments\NormalSegmentList;
use Tqxxkj\SimpleSql\Core\Conditions\Segments\OrderBySegmentList;

abstract class AbstractWrapper
{
    const ORDER_BY = 'order by';
    const GROUP_BY = 'group by';
    const HAVING = 'having';

    /**
     * @var NormalSegmentList 查询条件的组成片段, 比如 ['id', '=', '1']
     */
    public $normalSegmentList;

    /**
     * @var GroupBySegmentList group by 子句 片段
     */
    public $groupBySegmentList;

    /**
     * @var HavingSegmentList having 子句片段
     */
    public $havingSegmentList;

    /**
     * @var OrderBySegmentList order by 子句组成的片段，以空格分隔
     */
    public $orderBySegmentList;

    /**
     * @var array 需要绑定的参数缓存
     */
    public $paramIndexValuePairs = [];

    /**
     * @param string $column    列名
     * @param mixed  $value     值，单个值，或者数组[值，PDO 类型]
     * @param bool   $condition 是否需要组装该条件
     * @return $this
     */
    public function eq($column, $value, $condition = true): AbstractWrapper
    {
        $this->doIt($condition, "`{$column}`", '=', '?');
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
        $this->doIt($condition, "`{$column}`", '<>', '?');
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
        $this->doIt($condition, "`{$column}`", '>', '?');
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
        $this->doIt($condition, "`{$column}`", '>=', '?');
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
        $this->doIt($condition, "`{$column}`", '<', '?');
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
        $this->doIt($condition, "`{$column}`", '<=', '?');
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
        $this->doIt($condition, "`{$column}`", 'like', "CONCAT('%',?,'%')");
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
        $this->doIt($condition, "`{$column}`", 'like', "CONCAT('%',?)");
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
        $this->doIt($condition, "`{$column}`", 'like', "CONCAT(?,'%')");
        $this->addParamIndexValuePairs($value);
        return $this;
    }


    /**
     * in 查询
     * @param       $column
     * @param       $value_list
     * @param int   $type
     * @param bool  $condition
     * @return $this
     */
    public function in($column, $value_list, $type = \PDO::PARAM_STR, $condition = true): AbstractWrapper
    {
        if (!$value_list) {
            $value_list = [''];
        }
        $this->doIt(
            $condition,
            "`{$column}`",
            'in',
            sprintf('(%s)', join(',', array_fill(0, sizeof($value_list), '?')))
        );
        foreach ($value_list as $value) {
            $this->addParamIndexValuePairs($value);
        }
        return $this;
    }

    public function groupBy(...$columns)
    {
        if (!$columns) {
            return $this;
        }
        $this->doIt(true, self::GROUP_BY, ...$columns);
        return $this;
    }


    /**
     * 追加排序
     * @param        $column
     * @param bool   $isAsc
     * @param bool   $condition
     * @return AbstractWrapper
     */
    public function orderBy($column, $isAsc = true, $condition = true)
    {
        if (!$column) {
            return $this;
        }
        $mode = $isAsc ? 'asc' : 'desc';
        $this->doIt($condition, self::ORDER_BY, "`{$column}`", $mode);
        return $this;
    }


    /**
     * 添加片段
     * @param       $condition
     * @param mixed ...$sqlSegments
     */
    public function doIt($condition, ...$sqlSegments)
    {
        if (!$condition) {
            return;
        }
        switch ($sqlSegments[0]) {
            case self::ORDER_BY:
                $this->orderBySegmentList->addAll($sqlSegments);
                break;
            case self::GROUP_BY:
                $this->groupBySegmentList->addAll($sqlSegments);
                break;
            case self::HAVING:
                $this->havingSegmentList->addAll($sqlSegments);
                break;
            default: // 默认为 where 子句
                $this->normalSegmentList->addAll($sqlSegments);
        }
    }


    public function addParamIndexValuePairs($value)
    {
        $place_holder_index = sizeof($this->paramIndexValuePairs);
        $this->paramIndexValuePairs[$place_holder_index + 1] = is_array($value) ? [$value[0], $value[1]] : $value;
    }
}
