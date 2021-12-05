<?php


namespace Tqxxkj\SimpleSql\Dao;


use Tqxxkj\SimpleSql\Core\Conditions\Query\QueryWrapper;
use Tqxxkj\SimpleSql\Session\Defaults\DefaultSqlSession;

abstract class BaseDao
{
    /**
     * @var DefaultSqlSession
     */
    private $sqlSession;

    /**
     * 返回表名称
     * @return string
     */
    protected abstract function tableName(): string;

    /**
     * BaseDao constructor.
     * @param $sqlSession
     */
    public function __construct($sqlSession)
    {
        $this->sqlSession = $sqlSession;
    }

    protected function prepareTableConstruct()
    {

    }

    /**
     * @param $id
     * @param array $columnList
     * @return mixed|null
     * @throws \Exception
     */
    public function selectById0($id, $columnList = [])
    {
        $queryWrapper = QueryWrapper::get()->select(...$columnList)->eq('id', $id);
        $tableName = $this->tableName();
        $sql = "select $queryWrapper->sqlSelect from `$tableName` where `id`=?";
        return $this->sqlSession->selectOne($sql, $queryWrapper->toBindValues);
    }

    protected function selectBatchIds0($idList, $columnList = [])
    {
        QueryWrapper::get()
            ->select(...$columnList)
            ->in('id', $idList);
    }


    protected function selectList0(QueryWrapper $queryWrapper)
    {
        $sql = "select * from `$this->tableName`";
        $this->sqlSession->selectList($sql, $queryWrapper->toBindValues);
    }
}