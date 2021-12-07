<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;

class OrderBySegmentList extends AbstractISegmentList
{
    /**
     * order by 的预处理
     * @param array  $list
     * @param string $firstSegment
     * @param string $lastSegment
     * @return bool
     */
    protected function transformList(array &$list, string $firstSegment, string $lastSegment): bool
    {
        // 第一个是 order by 标志, 删除
        array_splice($list, 0, 1);
        // 以空格隔开, 组成整体 sql 片段，如: 'id desc'
        $sql = join(' ', $list);
        $list = [];
        array_push($list, $sql);
        return true;
    }

    protected function childrenSqlSegment(): string
    {
        if ($this->isEmpty()) {
            return '';
        }
        return ' order by ' . join(',', $this->segmentList);
    }
}