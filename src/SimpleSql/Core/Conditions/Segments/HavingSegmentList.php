<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;

class HavingSegmentList extends AbstractISegmentList
{
    /**
     * having 的预处理
     * 因为传递过来的 SQL 片段约定以 'having' 字符串开头，因此需要丢弃
     * @param array  $list
     * @param string $firstSegment
     * @param string $lastSegment
     * @return bool
     */
    protected function transformList(array &$list, string $firstSegment, string $lastSegment): bool
    {
        // 有可能多次调用 having 方法，所以非首次调用需要增加 and 条件
        if (sizeof($this->segmentList) !== 0) {
            array_push($this->segmentList, 'and');
        }
        array_splice($list, 0, 1);
        return true;
    }

    protected function childrenSqlSegment(): string
    {
        if ($this->isEmpty()) {
            return '';
        }
        return ' having ' . join(' ', $this->segmentList);
    }
}