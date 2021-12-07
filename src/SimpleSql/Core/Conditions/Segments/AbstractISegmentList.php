<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;

abstract class AbstractISegmentList
{
    /**
     * @var string[]
     */
    public $segmentList = [];

    /**
     * @var string
     */
    protected $lastValue;

    /**
     * @var bool
     */
    private $flushLastValue = false;

    private $sqlSegment = '';

    /**
     * @var bool 是否缓存结果集
     */
    private $cacheSqlSegment = true;

    public function addAll(array $sqlSegments)
    {
        $goOn = $this->transformList($sqlSegments, $sqlSegments[0], $sqlSegments[sizeof($sqlSegments) - 1]);
        if ($goOn) {
            $this->cacheSqlSegment = false;
            if ($this->flushLastValue) {
                $this->flushLastValue($sqlSegments);
            }
        }
        array_push($this->segmentList, ...$sqlSegments);
    }

    /**
     * @param array  $list
     * @param string $firstSegment
     * @param string $lastSegment
     * @return mixed
     */
    protected abstract function transformList(array &$list, string $firstSegment, string $lastSegment): bool;

    private function flushLastValue(array $list)
    {
        $this->lastValue = $list[sizeof($list) - 1];
    }

    function removeAndFlushLast()
    {
        array_splice($this->segmentList, -1, 1);
        $this->flushLastValue($this->segmentList);
    }

    public function getSqlSegment()
    {
        if ($this->cacheSqlSegment) {
            return $this->sqlSegment;
        }
        $this->cacheSqlSegment = true;
        $this->sqlSegment = $this->childrenSqlSegment();
        return $this->sqlSegment;
    }

    /**
     * 子类实现逻辑
     * @return string
     */
    protected abstract function childrenSqlSegment(): string;

    public function clear()
    {
        $this->lastValue = null;
        $this->sqlSegment = '';
        $this->cacheSqlSegment = true;
    }


    /**
     * @return bool
     */
    public function isEmpty()
    {
        return sizeof($this->segmentList) === 0;
    }

}
