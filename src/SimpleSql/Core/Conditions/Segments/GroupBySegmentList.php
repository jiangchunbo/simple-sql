<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;

class GroupBySegmentList extends AbstractISegmentList
{
    protected function transformList(array &$list, string $firstSegment, string $lastSegment): bool
    {
        array_splice($list, 0, 1);
        return true;
    }
}