<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;

class NormalSegmentList extends AbstractISegmentList
{

    protected function transformList(array &$list, string $firstSegment, string $lastSegment): bool
    {
        if (sizeof($list) === 1) {
            // TODO 只有 and() 以及 or() 以及 not() 会进入
            if ($firstSegment !== 'not') {
                // and 或者 or
                if ($this->isEmpty()) {
                    return false;
                }
                $matchLastAnd = $this->lastValue === 'and';
                $matchLastOr = $this->lastValue === 'or';
                if ($matchLastAnd || $matchLastOr) {
                    if ($matchLastAnd && $firstSegment === 'and') {
                        return false;
                    } else if ($matchLastOr && $firstSegment === 'or') {
                        return false;
                    } else {
                        array_splice($this->segmentList, -1, 1);
                        $this->lastValue = $this->segmentList[sizeof($this->segmentList) - 1];
                    }
                }
            } else {
                return false;
            }
        } else {
            if (in_array($this->lastValue, ['and', 'or']) && !$this->isEmpty()) {
                array_push($this->segmentList, 'and');
            }
        }
        return true;
    }

    protected function childrenSqlSegment(): string
    {
        if (in_array($this->lastValue, ['and', 'or'])) {
            $this->removeAndFlushLast();
        }
        return '(' . join(' ', $this->segmentList) . ')';
    }
}