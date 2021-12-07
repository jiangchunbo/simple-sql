<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;

class GroupBySegmentList extends AbstractISegmentList
{
    /**
     * group by 的预处理
     * 因为传递过来的 SQL 片段约定以 'group by' 字符串开头，因此需要丢弃
     * @param array  $list
     * @param string $firstSegment
     * @param string $lastSegment
     * @return bool
     */
    protected function transformList(array &$list, string $firstSegment, string $lastSegment): bool
    {
        array_splice($list, 0, 1);
        return true;
    }

    protected function childrenSqlSegment(): string
    {
        if ($this->isEmpty()) {
            return '';
        }
        return ' group by ' . join(',', $this->segmentList);
    }
}