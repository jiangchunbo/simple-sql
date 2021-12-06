<?php

namespace Tqxxkj\SimpleSql\Dao;

use Exception;
use PDO;
use Tqxxkj\SimpleSql\Core\Conditions\AbstractWrapper;
use Tqxxkj\SimpleSql\Core\Conditions\Query\Page;
use Tqxxkj\SimpleSql\Core\Conditions\Query\QueryWrapper;
use Tqxxkj\SimpleSql\Core\Conditions\Update\UpdateWrapper;
use Tqxxkj\SimpleSql\Session\SqlSession;

class BaseDao
{
    /**
     * @var SqlSession
     */
    protected $sqlSession;

    /**
     * @return SqlSession
     */
    public function getSqlSession(): SqlSession
    {
        return $this->sqlSession;
    }


    protected $tableName;

    /**
     * BaseDao constructor.
     * @param $sqlSession
     * @param $tableName
     */
    public function __construct($sqlSession, $tableName)
    {
        $this->sqlSession = $sqlSession;
        $this->tableName = $tableName;
    }

    protected function prepareTableConstruct()
    {

    }


    /**
     * @param array $entity
     * @return int
     * @throws Exception
     */
    public function insert(array &$entity): int
    {
        $sql = "insert into `%s`(%s) values(%s)";
        $paramIndexValuePairs = [];
        $columns = [];
        $values = [];
        $paramIndex = 0;
        foreach ($entity as $column => $value) {
            array_push($columns, "`$column`");
            array_push($values, "?");
            if (is_int($value)) {
                $paramIndexValuePairs[++$paramIndex] = [strval($value), PDO::PARAM_INT];
            } else {
                $paramIndexValuePairs[++$paramIndex] = [strval($value), PDO::PARAM_STR];
            }
        }
        $sql = sprintf($sql, $this->tableName, join(',', $columns), join(',', $values));
        return $this->sqlSession->insert($sql, $paramIndexValuePairs);
    }

    public function deleteById($id)
    {
        $queryWrapper = QueryWrapper::get()->eq('id', $id);
        $tableName = $this->tableName;
        $where = $this->where($queryWrapper);
        $sql = "select $queryWrapper->sqlSelect from `$tableName` $where";
        return $this->sqlSession->delete($sql, $queryWrapper->paramIndexValuePairs);
    }

    public function deleteBatchIds($idList)
    {
        $queryWrapper = QueryWrapper::get()->in('id', $idList);
        $tableName = $this->tableName;
        $where = $this->where($queryWrapper);
        $sql = "select $queryWrapper->sqlSelect from `$tableName` $where";
        return $this->sqlSession->delete($sql, $queryWrapper->paramIndexValuePairs);
    }

    public function updateById($entity)
    {
        $updateWrapper = UpdateWrapper::get();
        foreach ($entity as $column => $value) {
            if ($column === 'id') {
                $updateWrapper->eq('id', $value);
            } else {
                $updateWrapper->set($column, $value);
            }
        }
        $sqlSet = join(',', $updateWrapper->sqlSet);
        $where = $this->where($updateWrapper);
        $sql = "update `$this->tableName` set `$sqlSet` $where";
        return $this->sqlSession->update($sql, $updateWrapper->paramIndexValuePairs);
    }

    /**
     * @param int   $id
     * @param array $columnList
     * @return array
     * @throws Exception
     */
    public function selectById($id, $columnList = []): array
    {
        $queryWrapper = QueryWrapper::get()->select(...$columnList)->eq('id', $id);
        $tableName = $this->tableName;
        $where = $this->where($queryWrapper);
        $sql = "select $queryWrapper->sqlSelect from `$tableName` $where";
        return $this->sqlSession->selectOne($sql, $queryWrapper->paramIndexValuePairs);
    }

    protected function selectBatchIds($idList, $columnList = [])
    {
        $queryWrapper = QueryWrapper::get()->select(...$columnList)->in('id', $idList);
        $tableName = $this->tableName;
        $where = $this->where($queryWrapper);
        $sql = "select $queryWrapper->sqlSelect from `$tableName` $where";
        return $this->sqlSession->selectOne($sql, $queryWrapper->paramIndexValuePairs);
    }

    protected function selectOne(QueryWrapper $queryWrapper)
    {
        $where = $this->where($queryWrapper);
        $sql = "select * from `$this->tableName` $where limit 1";
        $this->sqlSession->selectList($sql, $queryWrapper->paramIndexValuePairs);
    }

    protected function selectCount(QueryWrapper $queryWrapper)
    {
        $where = $this->where($queryWrapper);
        $sql = "select count(*) from `$this->tableName` $where";
        $result = $this->sqlSession->selectOne($sql, $queryWrapper->paramIndexValuePairs)[0];
        return intval($result);
    }


    /**
     * @param QueryWrapper $queryWrapper
     * @throws Exception
     */
    protected function selectList(QueryWrapper $queryWrapper)
    {
        $where = $this->where($queryWrapper);
        $sql = "select * from `$this->tableName` $where";
        $this->sqlSession->selectList($sql, $queryWrapper->paramIndexValuePairs);
    }

    /**
     * @param Page         $page
     * @param QueryWrapper $queryWrapper
     * @throws Exception
     */
    protected function selectPage(Page $page, QueryWrapper $queryWrapper)
    {
        $offset = $page->getOffset();
        $limit = $page->getLimit();
        $where = $this->where($queryWrapper);
        $sql = "select * from `$this->tableName` $where limit $offset,$limit";
        $this->sqlSession->selectList($sql, $queryWrapper->paramIndexValuePairs);
    }

    /**
     * 得到 where 子句
     * @param AbstractWrapper $queryWrapper
     * @return string
     */
    public function where(AbstractWrapper $queryWrapper)
    {
        $segment = $queryWrapper->normalSegmentList->segmentList;
        return $segment ? 'where ' . join(' ', $segment) : '';
    }
}