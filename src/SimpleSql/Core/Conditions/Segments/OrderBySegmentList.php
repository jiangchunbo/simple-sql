<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;

class OrderBySegmentList extends AbstractISegmentList
{
    protected function transformList(array &$list, string $firstSegment, string $lastSegment): bool
    {
        array_splice($list, 0, 1); // 第一个是 ORDER_BY 标志, 删除
        $sql = join(' ', $list); // 以空格隔开
        $list = [];
        array_push($list, $sql);
        return true;
    }
}