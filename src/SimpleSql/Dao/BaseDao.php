<?php

namespace Tqxxkj\SimpleSql\Dao;

use Exception;
use PDO;
use Tqxxkj\SimpleSql\Core\Conditions\AbstractWrapper;
use Tqxxkj\SimpleSql\Core\Conditions\Query\Page;
use Tqxxkj\SimpleSql\Core\Conditions\Query\QueryWrapper;
use Tqxxkj\SimpleSql\Core\Conditions\Update\UpdateWrapper;
use Tqxxkj\SimpleSql\Core\Conditions\Wrapper;
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
     * 插入一条数据
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
                $paramIndexValuePairs[++$paramIndex] = [$value, PDO::PARAM_INT];
            } else {
                $paramIndexValuePairs[++$paramIndex] = [strval($value), PDO::PARAM_STR];
            }
        }
        $sql = sprintf($sql, $this->tableName, join(',', $columns), join(',', $values));
        $generatedKey = 0;
        $result = $this->sqlSession->insert($sql, $paramIndexValuePairs, $generatedKey);
        $entity['id'] = $generatedKey;
        return $result;
    }

    /**
     * 以 ID 删除
     * @param $id
     * @return int
     */
    public function deleteById($id)
    {
        $queryWrapper = UpdateWrapper::get()->eq('id', $id);
        $tableName = $this->tableName;
        $where = $this->where($queryWrapper);
        $sql = "delete from `$this->tableName` $where";
        return $this->sqlSession->delete($sql, $queryWrapper->paramIndexValuePairs);
    }

    /**
     * 多 ID 删除
     * @param $idList
     * @return int
     */
    public function deleteBatchIds($idList)
    {
        $queryWrapper = QueryWrapper::get()->in('id', $idList);
        $tableName = $this->tableName;
        $where = $this->where($queryWrapper);
        $sql = "select $queryWrapper->sqlSelect from `$tableName` $where";
        return $this->sqlSession->delete($sql, $queryWrapper->paramIndexValuePairs);
    }

    /**
     * @param $entity
     * @return int
     * @throws Exception
     */
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
     * @param array   $entity
     * @param Wrapper $queryWrapper
     * @return int
     * @throws Exception
     */
    public function update(array $entity, Wrapper $updateWrapper)
    {
        foreach ($entity as $column => $value) {
            $updateWrapper->set($column, $value);
        }
        $sqlSet = $updateWrapper->getSqlSet();
        $where = $this->where($queryWrapper);
        $sql = "update `$this->tableName` set `$sqlSet` $where";
        return $this->sqlSession->update($sql, $queryWrapper->paramIndexValuePairs);
    }

    /**
     * 根据主键 ID 获取一条记录
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function selectById($id): array
    {
        $sql = "select * from `$this->tableName` where `id`=?";
        return $this->sqlSession->selectOne($sql, [
            1 => [$id, PDO::PARAM_INT]
        ]);
    }

    /**
     * 根据多个主键 ID 获取一条记录
     * @param array $idList id 列表
     * @return array
     * @throws Exception
     */
    public function selectBatchIds($idList)
    {
        $queryWrapper = QueryWrapper::get()->in('id', $idList);
        $sql = $this->getSelectSql($queryWrapper);
        return $this->sqlSession->selectList($sql, $queryWrapper->paramNameValuePairs);
    }

    public function getSelectSql(QueryWrapper $queryWrapper = null): string
    {
        $sql = "select ";
        if (isset($queryWrapper) && isset($queryWrapper->sqlSelect)) {
            $sql .= $queryWrapper->sqlSelect;
        }
        $sql .= " from `$this->tableName`";
        if (isset($queryWrapper)) {
            $where = '';
            if (isset($queryWrapper->entity)) {
                if (isset($queryWrapper->entity['id'])) {
                    $where .= 'id=:ew.entity.id';
                }
            }
            if ($queryWrapper->getSqlSegment() && !$queryWrapper->isEmptyOfWhere()) {
                if (!$queryWrapper->isEmptyOfEntity() && !$queryWrapper->isEmptyOfNormal()) {
                    $where .= ' and';
                }
                $where .= $queryWrapper->getSqlSegment();
            }
            if (trim($where)) {
                $sql .= " where $where";
            }
            if ($queryWrapper->getSqlSegment() && $queryWrapper->isEmptyOfWhere()) {
                $sql .= $queryWrapper->getSqlSegment();
            }
        }
        return $sql;
    }

    /**
     *
     * @param QueryWrapper|null $queryWrapper
     * @throws Exception
     */
    public function selectOne(QueryWrapper $queryWrapper = null)
    {
        $sql = $this->getSelectSql($queryWrapper);
        $sql .= " limit 1";
        return $this->sqlSession->selectList($sql, $queryWrapper->paramNameValuePairs)[0];
    }

    /**
     * @param QueryWrapper $queryWrapper
     * @return int
     * @throws Exception
     */
    public function selectCount(QueryWrapper $queryWrapper): int
    {
        $queryWrapper->select('count(*)');
        $sql = $this->getSelectSql($queryWrapper);
        $result = $this->sqlSession->selectList($sql, $queryWrapper->paramNameValuePairs)[0]['count(*)'];
        return intval($result);
    }


    /**
     * @param QueryWrapper $queryWrapper
     * @return array
     * @throws Exception
     */
    public function selectList(QueryWrapper $queryWrapper): array
    {
        $sql = $this->getSelectSql($queryWrapper);
        return $this->sqlSession->selectList($sql, $queryWrapper->paramNameValuePairs);
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
     * @param Wrapper $queryWrapper
     * @return string
     */
    public function where(Wrapper $wrapper)
    {
        $segment = $wrapper->getExpression()->getSqlSegment();
        return $segment ? 'where ' . join(' ', $segment) : '';
    }
}