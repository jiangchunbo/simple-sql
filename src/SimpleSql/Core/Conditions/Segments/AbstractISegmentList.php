<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;

abstract class AbstractISegmentList
{

    public $segmentList = [];

    protected $lastValue;

    /**
     * @param array  $list
     * @param string $firstSegment
     * @param string $lastSegment
     * @return mixed
     */
    protected abstract function transformList(array &$list, string $firstSegment, string $lastSegment): bool;

    public function addAll(array $sqlSegments)
    {
        $this->transformList($sqlSegments, $sqlSegments[0], $sqlSegments[sizeof($sqlSegments) - 1]);
        array_push($this->segmentList, ...$sqlSegments);
    }
}
