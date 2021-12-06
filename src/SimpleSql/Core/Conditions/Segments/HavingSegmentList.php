<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;

class HavingSegmentList extends AbstractISegmentList
{
    protected function transformList(array &$list, string $firstSegment, string $lastSegment): bool
    {
        if (sizeof($this->segmentList) !== 0) {
            array_push($this->segmentList, 'and');
        }
        array_splice($list, 0, 1);
        return true;
    }
}