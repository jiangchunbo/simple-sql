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
    }


}
