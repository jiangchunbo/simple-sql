<?php

namespace Tqxxkj\SimpleSql\Core\Conditions;

abstract class AbstractWrapper
{
    const ORDER_BY = 1;
    const GROUP_BY = 2;
    const HAVING = 3;

    /**
     * @var array 查询条件的组成片段, 比如 ['id', '=', '1']
     */
    public $normalSegmentList = [];

    /**
     * @var array group by 子句 片段
     */
    public $groupBySegmentList = [];

    /**
     * @var array having 子句片段
     */
    public $havingSegmentList = [];

    /**
     * @var array order by 子句组成的片段，以空格分隔
     */
    public $orderBySegmentList = [];


    /**
     * @var array 占位符对应的值
     */
    public $toBindValues = [];

    /**
     * @param string $column    列名
     * @param mixed  $value     值，单个值，或者数组[值，PDO 类型]
     * @param bool   $condition 是否需要组装该条件
     * @return $this
     */
    public function eq($column, $value, $condition = true)
    {
        $this->doIt($condition, "`{$column}`", '=', '?');
        $this->addBindValues($value);
        return $this;
    }

    /**
     * 不等于
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function ne($column, $value, $condition = true)
    {
        $this->doIt($condition, "`{$column}`", '<>', '?');
        $this->addBindValues($value);
        return $this;
    }

    /**
     * greater than
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return $this
     */
    public function gt($column, $value, $condition = true)
    {
        $this->doIt($condition, "`{$column}`", '>', '?');
        $this->addBindValues($value);
        return $this;
    }


    /**
     * greater equal than
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return $this
     */
    public function ge($column, $value, $condition = true)
    {
        $this->doIt($condition, "`{$column}`", '>=', '?');
        $this->addBindValues($value);
        return $this;
    }

    /**
     * 小于
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function lt($column, $value, $condition = true)
    {
        $this->doIt($condition, "`{$column}`", '<', '?');
        $this->addBindValues($value);
        return $this;
    }

    /**
     * 小于等于
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function le($column, $value, $condition = true)
    {
        $this->doIt($condition, "`{$column}`", '<=', '?');
        $this->addBindValues($value);
        return $this;
    }

    /**
     * 全模糊查询
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function like($column, $value, $condition = true)
    {
        $this->doIt($condition, "`{$column}`", 'like', "CONCAT('%',?,'%')");
        $this->addBindValues($value);
        return $this;
    }

    /**
     * 左模糊查询 %xx
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function likeLeft($column, $value, $condition = true)
    {
        $this->doIt($condition, "`{$column}`", 'like', "CONCAT('%',?)");
        $this->addBindValues($value);
        return $this;
    }

    /**
     * 右模糊
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return $this
     */
    public function likeRight($column, $value, $condition = true)
    {
        $this->doIt($condition, "`{$column}`", 'like', "CONCAT(?,'%')");
        $this->addBindValues($value);
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
    public function in($column, $value_list, $type = \PDO::PARAM_STR, $condition = true)
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
            $this->addBindValues($value);
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
     * @param bool   $is_asc
     * @param bool   $condition
     * @return AbstractWrapper
     */
    public function orderBy($column, $is_asc = true, $condition = true)
    {
        if (!$column) {
            return $this;
        }
        $order_mode = $is_asc ? '' : 'desc';
        $this->doIt($condition, self::ORDER_BY, "`{$column}`", $order_mode);
        return $this;
    }


    /**
     * 添加片段
     * @param $condition
     * @param ...$sql_segments
     */
    public function doIt($condition, ...$sql_segments)
    {
        if (!$condition) {
            return;
        }
        switch ($sql_segments[0]) {
            case self::GROUP_BY:
                array_splice($sql_segments, 0, 1); // 第一个是 GROUP_BY 标志, 删除
                array_push($this->groupBySegmentList, ...$sql_segments);
                break;
            case self::HAVING:
                break;
            case self::ORDER_BY:
                array_splice($sql_segments, 0, 1); // 第一个是 ORDER_BY 标志, 删除
                array_push($this->orderBySegmentList, ...$sql_segments);
                break;
            default: // 默认为 where 子句
                if ($this->normalSegmentList) {
                    array_push($this->normalSegmentList, 'and');
                }
                array_push($this->normalSegmentList, ...$sql_segments);
        }
    }


    public function addBindValues($value)
    {
        $place_holder_index = sizeof($this->toBindValues);
        $this->toBindValues[$place_holder_index + 1] = is_array($value) ? [$value[0], $value[1]] : $value;
    }
}
