<?php


namespace Tqxxkj\SimpleSql\Core\Conditions\Segments;


class MergeSegments
{
    /**
     * @var NormalSegmentList
     */
    private $normal;

    /**
     * @var GroupBySegmentList
     */
    private $groupBy;

    /**
     * @var HavingSegmentList
     */
    private $having;

    /**
     * @var OrderBySegmentList
     */
    private $orderBy;

    /**
     * @var string
     */
    private $sqlSegment = '';

    /**
     * @var bool
     */
    private $cacheSqlSegment = true;

    /**
     * MergeSegments constructor.
     */
    public function __construct()
    {
        $this->normal = new NormalSegmentList();
        $this->groupBy = new GroupBySegmentList();
        $this->having = new HavingSegmentList();
        $this->orderBy = new OrderBySegmentList();
    }

    public function add(...$sqlSegments)
    {
        switch ($sqlSegments[0]) {
            case 'order by':
                $this->orderBy->addAll($sqlSegments);
                break;
            case 'group by':
                $this->groupBy->addAll($sqlSegments);
                break;
            case 'having':
                $this->having->addAll($sqlSegments);
                break;
            default: // 默认为 where 子句
                $this->normal->addAll($sqlSegments);
        }
        $this->cacheSqlSegment = false;
    }

    /**
     * @return string
     */
    public function getSqlSegment(): string
    {
        if ($this->cacheSqlSegment) {
            return $this->sqlSegment;
        }
        $this->cacheSqlSegment = true;
        if ($this->normal->isEmpty()) {
            if (!$this->groupBy->isEmpty() || !$this->orderBy->isEmpty()) {
                $this->sqlSegment = $this->groupBy->getSqlSegment()
                    . $this->having->getSqlSegment()
                    . $this->orderBy->getSqlSegment();
            }
        } else {
            $this->sqlSegment = $this->normal->getSqlSegment()
                . $this->groupBy->getSqlSegment()
                . $this->having->getSqlSegment()
                . $this->orderBy->getSqlSegment();
        }
        return $this->sqlSegment;
    }

    public function clear()
    {
        $this->normal->clear();
        $this->groupBy->clear();
        $this->having->clear();
        $this->orderBy->clear();
    }

    /**
     * @return NormalSegmentList
     */
    public function getNormal(): NormalSegmentList
    {
        return $this->normal;
    }

    /**
     * @return GroupBySegmentList
     */
    public function getGroupBy(): GroupBySegmentList
    {
        return $this->groupBy;
    }

    /**
     * @return HavingSegmentList
     */
    public function getHaving(): HavingSegmentList
    {
        return $this->having;
    }

    /**
     * @return OrderBySegmentList
     */
    public function getOrderBy(): OrderBySegmentList
    {
        return $this->orderBy;
    }
}
