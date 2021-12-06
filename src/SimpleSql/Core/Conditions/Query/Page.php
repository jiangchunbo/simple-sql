<?php

namespace Tqxxkj\SimpleSql\Core\Conditions\Query;

class Page
{
    private $records;
    private $offset;
    private $page;
    private $limit;
    private $total;

    public function __construct($page = 1, $limit = 10)
    {
        $this->page = $page;
        $this->offset = ($page - 1) * $limit;;
        $this->limit = $limit;
    }

    public static function emptyPage()
    {
        return (new Page())->setRecords([])->setTotal(0);
    }

    /**
     * @return mixed
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * @param mixed $records
     * @return Page
     */
    public function setRecords($records)
    {
        $this->records = $records;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     * @return Page
     */
    public function setTotal($total)
    {
        $this->total = intval($total);
        return $this;
    }
}