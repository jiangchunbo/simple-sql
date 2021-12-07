<?php

namespace Tqxxkj\SimpleSql\Core\Conditions;

use PDO;
use Tqxxkj\SimpleSql\Core\Conditions\Segments\MergeSegments;

abstract class AbstractWrapper extends Wrapper
{
    /**
     * @var MergeSegments
     */
    private $expression;


    /**
     * @var int
     */
    private $paramNameSeq;

    /**
     * @var array 需要绑定的参数缓存
     */
    public $paramNameValuePairs = [];

    /**
     * AbstractWrapper constructor.
     */
    public function __construct()
    {
        $this->expression = new MergeSegments();
    }


    /**
     * 等于 equal
     * @param string $column    列名
     * @param mixed  $value     值
     * @param bool   $condition 是否需要组装该条件
     * @return $this
     */
    public function eq($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '=', $value);
        return $this;
    }

    /**
     * 不等于 not equal
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function ne($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '<>', $value);
        return $this;
    }

    /**
     * 大于 greater than
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return $this
     */
    public function gt($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '>', $value);
        return $this;
    }


    /**
     * 大于等于 greater equal
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return $this
     */
    public function ge($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '>=', $value);
        return $this;
    }

    /**
     * 小于 less than
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function lt($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '<', $value);
        return $this;
    }

    /**
     * 小于等于 less equal
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function le($column, $value, $condition = true): AbstractWrapper
    {
        $this->addCondition($condition, "`$column`", '<=', $value);
        return $this;
    }

    /**
     * 全模糊
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function like($column, $value, $condition = true): AbstractWrapper
    {
        if ($condition) {
            $this->appendSqlSegments("`$column`", 'like', "CONCAT('%'," . $this->formatParam($value) . ",'%')");
        }
        return $this;
    }

    /**
     * 模糊取反
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return AbstractWrapper
     */
    public function notLike($column, $value, $condition = true): AbstractWrapper
    {
        if ($condition) {
            $this->appendSqlSegments("`$column`", 'not like', "CONCAT('%'," . $this->formatParam($value) . ",'%')");
        }
        return $this;
    }

    /**
     * 左模糊
     * @param        $column
     * @param        $value
     * @param bool   $condition
     * @return $this
     */
    public function likeLeft($column, $value, $condition = true): AbstractWrapper
    {
        if ($condition) {
            $this->appendSqlSegments("`$column`", 'like', "CONCAT('%'," . $this->formatParam($value) . ")");
        }
        return $this;
    }

    /**
     * 右模糊
     * @param      $column
     * @param      $value
     * @param bool $condition
     * @return $this
     */
    public function likeRight($column, $value, $condition = true): AbstractWrapper
    {
        if ($condition) {
            $this->appendSqlSegments("`$column`", 'like', "CONCAT(" . $this->formatParam($value) . ",'%')");
        }
        return $this;
    }


    /**
     * in 查询
     * @param       $column
     * @param array $valueList
     * @param bool  $condition
     * @return $this
     */
    public function in($column, $valueList, $condition = true): AbstractWrapper
    {
        if ($valueList && $condition) {
            $inList = [];
            foreach ($valueList as $value) {
                $inList[] = $this->formatParam($value);
            }
            $this->appendSqlSegments(
                "`$column`",
                'in',
                '(' . join(',', $inList) . ')'
            );
        }
        return $this;
    }

    /**
     * 分组
     * @param array $columnList
     * @param bool  $condition
     * @return $this
     */
    public function groupBy($columnList = [], $condition = true): AbstractWrapper
    {
        if ($columnList && $condition) {
            if (!is_array($columnList)) {
                $columnList = [$columnList];
            }
            $this->appendSqlSegments('group by', ...$columnList);
        }
        return $this;
    }


    /**
     * 排序
     * @param string $column
     * @param bool   $isAsc
     * @param bool   $condition
     * @return AbstractWrapper
     */
    public function orderBy(string $column, $isAsc = true, $condition = true): AbstractWrapper
    {
        if ($column && $condition) {
            $mode = $isAsc ? 'asc' : 'desc';
            $this->appendSqlSegments('order by', "`$column`", $mode);
        }
        return $this;
    }

    /**
     * 支持 count(*) > {0} and 'name'={1} 传入
     * @param string $sqlHaving
     * @param array  $params
     * @param bool   $condition
     * @return $this
     */
    public function having(string $sqlHaving, array $params = [], $condition = true): AbstractWrapper
    {
        if ($sqlHaving && $condition) {
            foreach ($params as $i => $param) {
                $target = '{' . $i . '}';
                $sqlHaving = str_replace($target, $this->formatParam($param), $sqlHaving);
            }
            $this->appendSqlSegments('having', $sqlHaving);
        }
        return $this;
    }

    /**
     * 参数名称默认为 MPGENVAL + 自增数
     * @param $param
     * @return string 返回特定的参数注入占位符, ew.paramNameValuePairs.MPGENVAL 自增数
     */
    public function formatParam($param): string
    {
        ++$this->paramNameSeq;
        $genParamName = "MPGENVAL$this->paramNameSeq";
        $paramStr = ":ew_paramNameValuePairs_$genParamName";
        if (is_int($param)) {
            $this->paramNameValuePairs[$paramStr] = [$param, PDO::PARAM_INT];
        } else {
            $this->paramNameValuePairs[$paramStr] = [$param, PDO::PARAM_STR];
        }
        return $paramStr;
    }

    /**
     * 添加片段
     * @param bool   $condition
     * @param string $column
     * @param string $sqlKeyword
     * @param mixed  $value
     */
    public function addCondition($condition, $column, $sqlKeyword, $value)
    {
        if (!$condition) {
            return;
        }
        $this->appendSqlSegments($column, $sqlKeyword, $this->formatParam($value));
    }

    public function appendSqlSegments(...$sqlSegments)
    {
        $this->expression->add(...$sqlSegments);
    }


    public function addParamIndexValuePairs($value)
    {
//        $place_holder_index = sizeof($this->para);
//        $this->paramIndexValuePairs[$place_holder_index + 1] = $value;
    }

    /**
     * @return string
     */
    public function getSqlSegment(): string
    {
        return $this->expression->getSqlSegment();
    }

    public function getExpression(): MergeSegments
    {
        return $this->expression;
    }
}
